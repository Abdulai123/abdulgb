<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MarketKey;
use App\Models\Unauthorize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use gnupg;
use Illuminate\Http\Response;

class GeneralController extends Controller
{

    public static function encodeImages($filesFolder = "Market_Images")
    {
        $marketImages = [];
        // Get all files from the specified folder
        $files = Storage::disk('public')->files($filesFolder);

        foreach ($files as $file) {
            // Get the icon name without extension
            $iconName = pathinfo($file, PATHINFO_FILENAME);

            // Read the file content and encode it to base64
            $base64Image = base64_encode(Storage::disk('public')->get($file));

            // Add the base64 encoded image to the array with icon name as key
            $marketImages[$iconName] = $base64Image;
        }
        // Return the array with key 'Icons' containing the encoded icons
        return $marketImages;
    }


    public static function processAndStoreImage($file, $folder)
    {

        // Ensure that $file is a valid file object
        if (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
            throw new \InvalidArgumentException('Invalid file provided.');
        }

        // Check file type
        $allowedTypes = ['jpeg', 'png', 'jpg'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }

        // Use hashName() to generate a unique, hashed filename
        $uniqueFilename = time() . '_' . $file->hashName();

        // Construct the full path where the image will be stored
        $folderPath = storage_path("app/public/{$folder}");
        $imagePath = "{$folderPath}/{$uniqueFilename}";

        // Check if the specified folder exists, and create it if not
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true); // Adjust the permission based on your needs
        }

        // Move the uploaded file to the specified folder
        $moveSuccess = $file->move($folderPath, $uniqueFilename);

        // Check if the file move operation was successful
        if (!$moveSuccess) {
            throw new \RuntimeException('Failed to move the file to the destination folder.');
        }
        $iconName = pathinfo($uniqueFilename, PATHINFO_FILENAME);
        // Return only the filename
        return $iconName;
    }


    public function canary()
    {
        return view('User.canary', [
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
            'canaries'  => MarketKey::all(),
        ]);
    }

    public function pgpKeySystem(Request $request)
    {
        $user = auth()->user();


        if ($request->has('skip')) {
            // Flush the 'ask_pgp' session
            Session::forget('ask_pgp');

            return redirect('/');
        } elseif ($request->has('pgp_token')) {
            // validate the user token
            $request->validate([
                'pgp_token' => 'required|min:10|max:10',
            ]);

            if ($request->pgp_token !== session('global_token')) {
                return redirect()->back()->withErrors('Invalid token, check you pgp key well.');
            }

            $enable2fa = $request->has('enable2fa');
            $public_key  = session('public_key');

            return $this->addPgpKey($public_key, $enable2fa ?? false);

            // validate the user pgp key.
        } elseif ($request->has('public_key')) {
            // Validate the request
            $request->validate([
                'public_key' => 'required',
            ]);

            try {
                // Initialize GnuPG
                $gpg = new gnupg();

                // Set error mode to exception
                $gpg->seterrormode(gnupg::ERROR_EXCEPTION);

                // Import the public key and get the key fingerprint
                $importedKey = $gpg->import($request->public_key);
                $fingerprint = $importedKey['fingerprint'];

                // Get information about the key using the fingerprint
                $keyInfo = $gpg->keyinfo($fingerprint);
                $userName    = $keyInfo[0]['uids'][0]['name'];
                $expired     =  $keyInfo[0]['subkeys'][0]['expired'];
                $is_secret   = $keyInfo[0]['subkeys'][0]['is_secret'];
                $invalid   = $keyInfo[0]['subkeys'][0]['invalid'];

                // Validate each field
                // $this->validateField($userName, 'Name', $user->public_name);
                $this->validateExpiration($expired);
                $this->validateSecret($is_secret);
                $this->validateInvalid($invalid);

                // set the public key in a session for later
                session(['public_key' => $request->public_key]);

                return redirect()->back()->with('encrypted_message', $this::encryptPGPMessage($request->public_key))->with('encrypted_message_verify', true);
                // Do further processing with the key information
            } catch (\Exception $e) {
                // If an exception occurs, redirect back with the error message
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        }

        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    private function validateField($value, $fieldName, $expectedValue = null)
    {
       // Remove white spaces from both values for comparison
    $cleanedValue = preg_replace('/\s+/', '', $value);
    $cleanedExpectedValue = preg_replace('/\s+/', '', $expectedValue);

    // Perform case-insensitive comparison
    if (strcasecmp($cleanedValue, $cleanedExpectedValue) !== 0) {
        throw new \Exception("Validation failed for $fieldName: Your public name (ignoring white spaces) doesn't match the public pgp key user which is: $cleanedExpectedValue");
    }
    
    }

    private function validateExpiration($expired)
    {
        if ($expired) {
            throw new \Exception("Validation failed: Key has expired.");
        }
    }

    private function validateSecret($is_secret)
    {
        if ($is_secret) {
            throw new \Exception("Validation failed: Key is marked as secret.");
        }
    }

    private function validateInvalid($invalid)
    {
        if ($invalid) {
            throw new \Exception("Validation failed: Key is marked as invalid.");
        }
    }

    private function addPgpKey($key, $fa = false)
    {
        // Retrieve the authenticated user
        $user = auth()->user();

        // Check if the user is authenticated
        if ($user) {
            if ($fa) {
                $user->twofa_enable = true;
            }

            // Update the pgp_key attribute
            $user->pgp_key = $key;

            if (auth()->user()->role == 'store') {
                $user->store->store_pgp = $key;
                $user->store->save();
            }

            // Save the user model
            $user->save();

            // Flush the 'ask_pgp and public key' session
            Session::forget('ask_pgp');
            Session::forget('public_key');
            session(['pgp_verified' => true]);

            // Optionally, you can return a success message or do other tasks
            return redirect()->back()->with('success', "PGP key added successfully.");
        } else {
            // If the user is not authenticated, return an error message
            return redirect()->back()->withErrors("Error: User not authenticated.");
        }
    }

    public static function encryptPGPMessage($public_key)
    {
        try {
            $gpg = new gnupg();

            // Set error mode to exception
            $gpg->seterrormode(gnupg::ERROR_EXCEPTION);

            $importedKey = $gpg->import($public_key);

            // Check if the import was successful
            if ($importedKey === false) {
                throw new Exception('Failed to import public key: ' . $gpg->geterror());
            }

            $fingerprint = $importedKey['fingerprint'];

            // Add the recipient's public key for encryption
            $gpg->addencryptkey($fingerprint);

            // Message to be encrypted
            $token = Str::random(10);
            session(['global_token' => $token]); // Store the token in the session
            $message = "
                Welcome Back To Whales Market (❁´◡`❁)!
                
                     Here is your token: $token
                
                Thank you for choosing our market, stay safe.";

            // Encrypt the message
            $encryptedMessage = $gpg->encrypt($message);

            if ($encryptedMessage === false) {
                return dd('Encryption failed: ' . $gpg->geterror());
            }

            // Define delimiters with regular expressions
            $delimiterStart = '/-----BEGIN PGP MESSAGE-----/';
            $delimiterEnd = '/-----END PGP MESSAGE-----/';

            // Add line break before and after the delimiters
            $formattedMessage = preg_replace($delimiterStart, "-----BEGIN PGP MESSAGE-----<br>", $encryptedMessage);
            $formattedMessage = preg_replace($delimiterEnd, "<br>-----END PGP MESSAGE-----", $formattedMessage);

            // If everything went well, return the encrypted message
            return $formattedMessage;
        } catch (Exception $e) {
            // Log or handle the exception
            return 'Error in encrypt PGP message, message modmail on dread forum: ' . $e->getMessage();
        }
    }



    public static function  encryptPGPNotes($note, $public_key)
    {
        $gpg = new gnupg();

        $importedKey = $gpg->import($public_key);


        if (!$importedKey) {
            return $note;
        }
        $fingerprint = $importedKey['fingerprint'];
        // Get information about the key using the fingerprint
        $keyInfo = $gpg->keyinfo($fingerprint);
        $expired     =  $keyInfo[0]['subkeys'][0]['expired'];
        $invalid   = $keyInfo[0]['subkeys'][0]['invalid'];

        if ($invalid) {
            return $note;
        }

        // if the key expired return the use note
        if ($expired) {
            return $note;
        }

        // Add the recipient's public key for encryption
        $gpg->addencryptkey($fingerprint);

        // Encrypt the message
        $encryptedMessage = $gpg->encrypt($note);

        // Define delimiters with regular expressions
        $delimiterStart = '/-----BEGIN PGP MESSAGE-----/';
        $delimiterEnd = '/-----END PGP MESSAGE-----/';

        // Add line break before and after the delimiters
        $formattedMessage = preg_replace($delimiterStart, "-----BEGIN PGP MESSAGE-----", $encryptedMessage);
        $formattedMessage = preg_replace($delimiterEnd, "-----END PGP MESSAGE-----", $formattedMessage);

        // If everything went well, return the encrypted message
        return  $formattedMessage;
    }

    public function userPgpSystem(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'secret_code' => 'required|min:6|max:6',
            'public_key' => 'required|min:100',
        ]);

        if (auth()->user()->role != 'admin' && auth()->user()->role != 'senior') {
            $request->validate([
                'captcha'     => 'required|min:8|max:8',
            ]);

            if ($request->captcha != session('captcha')) {
                return redirect()->back()->withErrors('Invalid captcha code.');
            }
        }

        if ($request->secret_code != $user->pin_code) {
            return redirect()->back()->withErrors('Invalid secret code.');
        }

        if ($request->has('public_key')) {
            session(['public_key' => $request->public_key]);
        }


        try {
            // Initialize GnuPG
            $gpg = new gnupg();

            // Set error mode to exception
            $gpg->seterrormode(gnupg::ERROR_EXCEPTION);

            // Import the public key and get the key fingerprint
            $importedKey = $gpg->import($request->public_key);
            $fingerprint = $importedKey['fingerprint'];

            // Get information about the key using the fingerprint
            $keyInfo = $gpg->keyinfo($fingerprint);

            $userName    = $keyInfo[0]['uids'][0]['name'];
            $expired     =  $keyInfo[0]['subkeys'][0]['expired'];
            $is_secret   = $keyInfo[0]['subkeys'][0]['is_secret'];
            $invalid   = $keyInfo[0]['subkeys'][0]['invalid'];

            // Validate each field
            // $this->validateField($userName, 'Name', $user->public_name);
            $this->validateExpiration($expired);
            $this->validateSecret($is_secret);
            $this->validateInvalid($invalid);

            return redirect()->back()->with('encrypted_message', $this::encryptPGPMessage($request->public_key))->with('encrypted_message_verify', true);
        } catch (\Exception $e) {
            // If an exception occurs, redirect back with the error message
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    public function captcha(Session $session)
    {
        $captcha = Str::random(8);
    
        $currentTime = time();
        $newTime = $currentTime + 60;
        Session::put('captcha', $captcha);
        Session::put('captcha_time', $newTime);
    
        $imageWidth = 100;
        $imageHeight = 50;
        $image = imagecreate($imageWidth, $imageHeight);
    
        if (!$image) {
            abort(500, 'Error creating captcha image');
        }
    
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 20, 20, 15, $captcha, $textColor);
    
        for ($i = 0; $i < 10; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, rand(0, 200), rand(0, 50), rand(0, 200), rand(0, 50), $color);
        }
    
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
    
        imagedestroy($image);
        // Return the base64-encoded image data
        return  $imageData;
    }
    

   
    public static function captchaGen(){
        $captchachars = "ACDEFGHJKLMNPQRSTUVWXYZ023456789";
        $length = strlen($captchachars) - 1;
        $code = '';
    
        for ($i = 0; $i < 5; ++$i) {
            $code .= $captchachars[mt_rand(0, $length)];
        }
    
        //echo '<tr id="captcha"><td><br>';
        $im = imagecreatetruecolor(150, 200); //create image with and height
        $bg = imagecolorallocate($im, 0, 0, 0); //create the image bg color
        $fg = imagecolorallocate($im, 255, 255, 255); // font color
        imagefill($im, 0, 0, $bg); // fill the image 
        $chars = [];
        $x = $y = 0;
    
        // Add random lines
        for ($i = 0; $i < 5; ++$i) {
            $lineColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
           // imageline($im, mt_rand(0, 150), mt_rand(0, 200), mt_rand(0, 150), mt_rand(0, 200), $lineColor);
           imagepolygon($im, array(mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200), mt_rand(0, 150), mt_rand(0, 200)), 3, $lineColor);
        }
    
        for ($i = 0; $i < 10; ++$i) {
            $found = false;
    
            while (!$found) {
                $x = mt_rand(10, 140);
                $y = mt_rand(10, 180);
                $found = true;
    
                foreach ($chars as $char) {
                    if ($char['x'] >= $x && ($char['x'] - $x) < 25) {
                        $found = false;
                    } elseif ($char['x'] < $x && ($x - $char['x']) < 25) {
                        $found = false;
                    }
    
                    if (!$found) {
                        if ($char['y'] >= $y && ($char['y'] - $y) < 25) {
                            break;
                        } elseif ($char['y'] < $y && ($y - $char['y']) < 25) {
                            break;
                        } else {
                            $found = true;
                        }
                    }
                }
    
                $chars[] = ['x' => 0, 'y' => 0];
                $chars[$i]['x'] = $x;
                $chars[$i]['y'] = $y;
                
    
                if ($i < 5) {
                                // Introduce character distortions
            $distortion = mt_rand(-5, 10);
            imagechar($im, 5, $chars[$i]['x'] + $distortion, $chars[$i]['y'], $captchachars[mt_rand(0, $length)], $fg);

                } else {
                    imagechar($im, 5, $chars[$i]['x'], $chars[$i]['y'], $code[$i - 5], $fg);
                }
            }
        }
    
        $follow = imagecolorallocate($im, 200, 0, 0);
        imagearc($im, $chars[5]['x'] + 4, $chars[5]['y'] + 8, 16, 16, 0, 360, $follow);
    
        // for ($i = 5; $i < 9; ++$i) {
        //     imageline($im, $chars[$i]['x'] + 4, $chars[$i]['y'] + 8, $chars[$i + 1]['x'] + 4, $chars[$i + 1]['y'] + 8, $follow);
        // }

        for ($i = 5; $i < 9; ++$i) {
            echo '<img alt="" src="data:image/gif;base64,';
            ob_start();
            imagegif($im);
            imagedestroy($im);
            echo base64_encode(ob_get_clean()) . '">';
        }
    

    }
    
    
    

    public static function logUnauthorized(Request $request, $title, $contens = "Unauthorize access has been log.")
    {
        // Log unauthorized attempt
        $unauthorize = new Unauthorize();
        $unauthorize->user_id = auth()->user()->id ?? 1;
        $unauthorize->title = $title;
        $unauthorize->content = $contens;
        $unauthorize->url = $request->path();
        $unauthorize->role = auth()->user()->role ?? "Auto mod";
        $unauthorize->save();
    }
}
