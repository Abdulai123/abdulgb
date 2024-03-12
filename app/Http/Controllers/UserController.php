<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Category;
use App\Models\FavoriteListing;
use App\Models\FiatCurrency;
use App\Models\MarketFunction;
use App\Models\MessageStatus;
use App\Models\News;
use App\Models\NewStore;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Product;
use App\Models\Referral;
use App\Models\Store;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('Admin.index', [
            'user'  => auth()->user(),
            'users' => $users,
            'icon' => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'action' => 'users',
            'name' => 'Users',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($action)
    {
        // if (!session('ddos_visited')) {
        //     return redirect('/');
        // }

        // if (!session('waited')) {
        //     return redirect('/');
        // }

        if ($action == 'login') {
            $currentTime = time();
            $newTime = $currentTime + 120;
            Session::put('captcha_time', $newTime);
            return view('Auth.login', ['icon' => GeneralController::encodeImages()]);
        } else if ($action == 'signup') {
            return view('Auth.register', ['icon'   => GeneralController::encodeImages()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        foreach (\App\Models\MarketFunction::all() as $function) {
            if ($function->name == 'login' && $function->enable === 0) {
                return redirect()->back()->withErrors('Signup is currently disable by admin or mods, come back later, sorry mate..');
            }
        }

        if (session('signup')) {
            return redirect()->back()->withErrors('Stop spamming the system, a notification has been sent to admin about this action, you are trying to create second account while you have a signup session on.');
        }

        if (MarketFunction::where('name', 'signup')->first()->enable === 0) {
            return redirect()->back()->withErrors(['SignUp is currently disable by admin or mods, come back later, sorry mate..']);
        }

        // if (session('captcha_time') < now()->timestamp) {
        //     return redirect()->back()->withErrors(['Form expired, refresh again to get new csrf.']);
        // }

        //if ($request->has('captcha')) {
        // if (session('captcha_time') < now()->timestamp) {
        //     return redirect()->back()->withErrors(['Captcha code expired.']);
        // }

        //  if ($request->captcha !== session('auth_captcha')) {
        //  return redirect()->back()->withErrors(['Captcha code is invalid.'])->withInput();
        //  }
        // } else {
        //     return redirect()->back();
        // }

        // Check if the password and confirm_password match
        if ($request->password !== $request->confirm_password) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['confirm_password' => 'The password and confirm password do not match.'])->withInput();
        }

        $storeKey = Str::random(128);

        // Create the user
        $user = User::create([
            'public_name'       => $request->public_name,
            'private_name'      => $request->private_name,
            'store_key'         => $storeKey,
            'login_passphrase'  => $request->login_passphrase,
            'pin_code'          => $request->pin_code,
            'referral_link'     => $request->public_name,
            'password'          => bcrypt($request->password),
        ]);

        // craete user default currency
        $fiat = new FiatCurrency();
        $fiat->user_id = $user->id;
        $fiat->save();

        if (!empty($request->referred_link) && $request->referred_link != null) {
            $referred_by = User::where('public_name', $request->referred_link)->first();
            $new_referral = new Referral();
            $new_referral->user_id = $referred_by->id;
            $new_referral->referred_user_id = $user->id;
            $rs = $new_referral->save();
            $notificationType = NotificationType::where('action', 'used')->where('icon', 'referral')->first();

            if ($rs && $notificationType) {
                NotificationController::create($referred_by->id, null, $notificationType->id);
            }
        }
        // Create a new wallet for the user
        $newWallet = new Wallet([
            'user_id'       => $user->id,
            'balance'       => 100.00,
        ]);

        $newWallet->save();

        session(['signup' => true]);

        return redirect('/auth/login')->with('success', 'You have successfully created an account. Please log in now!');
    }

    // public function showUser($name = null, User $user)
    // {

    //     if ($user->role == 'admin' && $user->id < 10) {
    //         return $this->userIndex(null, $name, $user);
    //     }

    //     return abort(404);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }


    public function authLogin(Request $request)
    {
        // Validate the login data
        $request->validate([
            'private_name' => 'required|string|min:3|max:80',
            'password' => 'required|string|min:3|max:128',
            'session_timer' => 'required|integer|in:30,1,2,3,4,5,6,7,8,9,10,11,12',
        ]);

        // Count the number of login fails 'captcha' => 'required|min:8|max:8',
        $fail_time = session('fail_time') ?? 0;

        // Check if the captcha is provided and validate it  'captcha' => 'required|min:8|max:8',
        // if ($request->has('captcha')) {
        //     if (session('auth_captcha_time') < now()->timestamp) {
        //         session(['fail_time' => $fail_time + 1]);
        //         return redirect()->back()->withErrors(['login' => 'Captcha code expired.'])->withInput();
        //     }

        //     if ($request->captcha != session('auth_captcha')) {
        //         session(['fail_time' => $fail_time + 1]);
        //         return redirect()->back()->withErrors(['login' => 'Captcha code is invalid.'])->withInput();
        //     }
        // }

        // Check if the user exists
        $user = User::where('private_name', $request->private_name)->first();

        if (MarketFunction::where('name', 'login')->first()->enable === 0) {
            if (($user->role != 'admin' || $user->role != 'senior' || $user->role != 'junior')) {
                return redirect()->back()->withErrors(['login' => 'Login is currently disable by admin or mods, come back later, sorry mate..']);
            }
        }

        // Check if the user is banned
        if ($user && $user->status == 'banned') {
            session(['fail_time' => $fail_time + 1]);
            return redirect()->back()->withErrors(['login' => 'This account has been banned.'])->withInput();
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['private_name' => $request->private_name, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user->last_seen = now();
            $user->save();

            if (session('signup')) {
                session(['let_welcome' => true]);
            }

            // Reset fail_time on successful login
            session(['fail_time' => 0]);

            // Set session timer
            if ($request->session_timer == 30) {
                session(['session_timer' => ($request->session_timer * 60)]);
            } else {
                session(['session_timer' => ($request->session_timer * (60 * 60))]);
            }


            // Redirect to the desired location after successful login
            return redirect('/');
        }

        // Authentication failed
        session(['fail_time' => $fail_time + 1]);
        return redirect()->back()->withErrors(['login' => 'Invalid private name or password.'])->withInput();
    }



    public function openstore(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'storeProfile' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'selling' => 'required|string|max:5000',
            'shipto' => 'sometimes|nullable|string|',
            'shipfrom' => 'sometimes|nullable|string|',
            'storeDesc' => 'required|string|min:50|max:5000',
            'sellOn' => 'sometimes|nullable|string|min:1',
            'proof1' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'proof2' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'proof3' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'store_key' => 'required|string|min:64|max:256',
        ]);

        if ($data['store_key'] != $user->store_key) {
            GeneralController::logUnauthorized($request, 'fake store key', 'User provide fake store key: ' . $request->store_key);

            return redirect()->back()->withErrors('Invalid store key, too much fail attempt might get your account banned!!!');
        }

        if ($user->store_status == 'pending') {
            return redirect()->back()->withErrors('Your store is still pending, too much fail attempt might get your account banned!!!');
        }

        $newStore = new NewStore();
        $newStore->store_name = $user->public_name;
        $newStore->user_id = $user->id;
        $newStore->selling = $data['selling'];
        $newStore->ship_to = $data['shipto'] ?? 'worldwide';
        $newStore->ship_from = $data['shipfrom'] ?? 'worldwide';
        $newStore->store_description = $data['storeDesc'];
        $newStore->sell_on = $data['sellOn'];

        // Process and store images
        $newStore->proof1 = GeneralController::processAndStoreImage($data['proof1'], 'Upload_Images');
        $newStore->proof2 = GeneralController::processAndStoreImage($data['proof2'], 'Upload_Images');
        $newStore->proof3 = GeneralController::processAndStoreImage($data['proof3'], 'Upload_Images');
        $newStore->avater = GeneralController::processAndStoreImage($data['storeProfile'], 'Upload_Images');

        // Save the new store
        $newStore->save();

        $user->store_status = 'pending';
        $user->save();

        return redirect()->back()->with('success', 'Your store has been added please wait for apporval.');
    }

    public function userLogout()
    {
        // Log out the authenticated user
        auth()->logout();
        Session::flush();
        Session::regenerate();
        // Redirect the user to the '/' page
        return redirect('/');
    }

    private function userIndex($action, $name, $user)
    {
        $is_parent_category = false;
        $is_sub_category = false;

        // Assuming the User model has a relationship named 'blockedStores'
        $userBlockedVendorIds = auth()->user()->blockedStores()->pluck('store_id')->toArray();

        $products = Product::inRandomOrder()
            ->where('status', 'Active')
            ->whereHas('store', function ($query) use ($userBlockedVendorIds) {
                $query->where('status', 'active') // Additional condition for active stores
                    ->whereNotIn('store_id', $userBlockedVendorIds);
            })
            ->paginate(20);

        $categoryName = null;

        return view('User.index', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'action' => $action,
            'name' => $name,
            'products' => $products,
            'is_parent_category' => $is_parent_category,
            'is_sub_category' => $is_sub_category,
            'categoryName' => $categoryName,
        ]);
    }

    public function changePassword(Request $request)
    {
        // Validate the form data
        $request->validate([
            'old-passwrd' => 'required|min:3|max:128',
            'new-passwrd' => 'required|min:3|max:128',
            'confirm-new-passwrd' => 'required|min:3|max:128',
            'secret_code' => 'required|numeric|min:6',
            'captcha' => 'required|string|min:8|max:10'
        ]);

        // 'captcha'      => 'required|string|min:8|max:8',
        if ($request->captcha != session('captcha')) {
            return redirect()->back()->withErrors('Wrong captcha code.');
        }

        // Check if the old password matches the current user's password
        if ($request->secret_code != auth()->user()->pin_code) {
            return redirect()->back()->withErrors(['secret_code' => 'Secret code incorrect.']);
        }

        if (Hash::check($request->input('old-passwrd'), Auth::user()->password)) {
            // Update the user's password
            $user = User::find(auth()->user()->id);
            $user->password = bcrypt($request->input('new-passwrd'));
            $user->save();

            // Redirect or return a success response
            return redirect()->back()->with('success', 'Password changed successfully!');
        } else {
            // Old password doesn't match, return with an error message
            return redirect()->back()->withErrors(['old-passwrd' => 'The old password is incorrect.']);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($name = null, $created_at = null, $action = null)
    {

        if (auth()->check()) {

            $user = auth()->user();

            if ($user->twofa_enable == 'yes' && !session('pgp_verified')) {
                //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
                $role = auth()->user()->role;

                switch ($role) {
                    case 'user':
                        return redirect('/auth/pgp/verify');
                        break;

                    case 'share':
                        return redirect('/auth/share/pgp/verify');
                        break;

                    case 'store':
                        return redirect('/auth/store/pgp/verify');
                        break;

                    case 'junior':
                        return redirect('/auth/staff/junior/pgp/verify');
                        break;

                    case 'senior':
                        return redirect('/auth/staff/senior/pgp/verify');
                        break;

                    case 'admin':
                        return redirect('/auth/whales/admin/pgp/verify');
                        break;

                    default:
                        return redirect('/auth/pgp/verify');
                        break;
                }
            }
            if ($user->role == 'user') {
                return $this->userIndex($action, $name, $user);
            } elseif ($user->role == 'store') {

                $store = Store::where('user_id', auth()->user()->id)->first();
                return redirect('/store/' . $store->store_name . '/show');
            } elseif ($user->role == 'junior') {

                return redirect('/junior/staff/' . $user->public_name . '/show');
            } elseif ($user->role == 'senior') {

                return redirect('/senior/staff/' . $user->public_name . '/show');
            } elseif ($user->role == 'admin' && $user->id < 10) {
                return redirect('whales/admin/' . $user->public_name . '/show');
            }
        } else {
            // if (!session('ddos_visited')) {
            //     session(['time_started' => time()]);
            //     return view('Auth.ddos', ['icon' => GeneralController::encodeImages()]);
            // } else {

            //     if ((session('time_started') + session('await_time')) > time() && !session('waited')) {
            //         session(['waited' => false]);
            //         return abort(403, 'Page refreshed; please restart your browser.');
            //     } else if (session('waited') == false) {
            //         session(['waited' => true]);
            //         return redirect('/auth/login');
            //     } else {
            //return redirect('/auth/login');
            // }
            return redirect('/auth/login');
        }
    }


    public function theme($user = null)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $user = auth()->user();

        if ($user->theme == 'white') {
            $user->theme = 'dark';
            $user->save();
            return redirect()->back();
        } elseif ($user->theme == 'dark') {
            $user->theme = 'white';
            $user->save();
            return redirect()->back();
        }
        return abort(404);
    }


    public function welcome($user, Request $request)
    {
        // Check if the 'understood' token is present in the request
        if ($request->has('understood')) {

            // Flush the 'let_welcome' session
            Session::forget('let_welcome');

            // Flush the 'signup' session
            Session::forget('signup');

            session(['ask_pgp' => true]);
            // Redirect to '/'
            return redirect('/');
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');

        // If the token is not present, return the original request
        return abort(404);
    }

    public function kickout()
    {
        // Flush all session data
        Session::flush();

        // Regenerate the session ID to enhance security
        Session::regenerate();

        // Redirect users back to the '/' page
        return redirect('/');
    }



    // 2fa display
    public function pgpVerify()
    {
        $user = auth()->user();
        session(['encrypted_message' => GeneralController::encryptPGPMessage($user->pgp_key)]);

        return view('User.verify_2fa');
    }


    // 2fa code verify
    public function pgpCodeVerify(Request $request)
    {
        $user = auth()->user();

        if ($request->pgp_token == session('global_token')) {
            session(['pgp_verified' => true]);
            return redirect('/');
        }



        session(['encrypted_message' => GeneralController::encryptPGPMessage($user->pgp_key)]);

        return redirect()->back();
    }

    public function storeKey(Request $request)
    {
        $user = auth()->user();
        if ($user->twofa_enable == 'no') {
            return redirect()->back()->withErrors('Enable 2FA to proceed please.');
        }

        if (MarketFunction::where('name', 'storekey')->first()->enable == 0) {
            return redirect()->back()->withErrors('The processes of generating a new store key is disable by admin, please open a support ticket.');
        }

        if ($user->show_key) {
            return redirect()->back()->withErrors('Your store key has been already been generated, please check your notifications.');
        }

        if (session('xmr') == null) {
            return redirect()->back()->withErrors('XMR api problem unable to generate a key now., please open a support ticket and past this message as the topic..');
        }

        $to_pay_in_xmr = \App\Models\StoreRule::where('is_xmr', true)->first()->name;
        $balance_to_xmr = ($user->wallet->balance / session('xmr'));

        if ($balance_to_xmr < $to_pay_in_xmr) {
            return redirect()->back()->withErrors('Your store key cannot be generated due to insufficent funds.');
        }

        // $amountInXMR = ($amountInAtomicUnits / 1e12);
        $xmr_usd = ($to_pay_in_xmr * session('xmr'));

        $user = auth()->user();
        $user->wallet->balance -= $xmr_usd;
        $user->wallet->save();

        // Add the rest to the escrow wallet
        $escrow = User::where('private_name', 'escrow')->where('role', 'senior')->first()->wallet;
        $escrow->balance += $xmr_usd;
        $escrow->save();


        $notificationType = NotificationType::where('action', 'key')->where('icon', 'store')->first();
        if ($notificationType) {
            NotificationController::create($user->id, null, $notificationType->id);
        }

        $user->show_key = true;
        $user->save();

        return redirect()->back()->with('success', 'Congratulations your store key has been generated successfully, please check you notifications.');
    }


    public function currency($user, $created_at, Request $request)
    {
        $auth = auth()->user();

        if ($auth->public_name !=  $user || strtotime($auth->created_at) != $created_at) {
            return redirect()->back();
        }

        $request->validate([
            'currency' => 'required|string|in:USD,EUR,JPY,GBP,CHF,CAD,AUD,CNY,SEK,NZD',
        ]);

        switch ($request->currency) {
            case 'USD':
                $auth->fiat->name = "United States Dollar (USD)";
                $auth->fiat->abbr = "USD";
                $auth->fiat->symbol = "$";
                $auth->fiat->save();
                break;
            case 'EUR':
                $auth->fiat->name = "Euro (EUR)";
                $auth->fiat->abbr = "EUR";
                $auth->fiat->symbol = "€";
                $auth->fiat->save();
                break;
            case 'JPY':
                $auth->fiat->name = "Japanese Yen (JPY)";
                $auth->fiat->abbr = "JPY";
                $auth->fiat->symbol = "¥";
                $auth->fiat->save();
                break;
            case 'GBP':
                $auth->fiat->name = "British Pound Sterling (GBP)";
                $auth->fiat->abbr = "GBP";
                $auth->fiat->symbol = "£";
                $auth->fiat->save();
                break;
            case 'CHF':
                $auth->fiat->name = "Swiss Franc (CHF)";
                $auth->fiat->abbr = "CHF";
                $auth->fiat->symbol = "CHF";
                $auth->fiat->save();
                break;
            case 'CAD':
                $auth->fiat->name = "Canadian Dollar (CAD)";
                $auth->fiat->abbr = "CAD";
                $auth->fiat->symbol = "$";
                $auth->fiat->save();
                break;
            case 'AUD':
                $auth->fiat->name = "Australian Dollar (AUD)";
                $auth->fiat->abbr = "AUD";
                $auth->fiat->symbol = "A$";
                $auth->fiat->save();
                break;
            case 'CNY':
                $auth->fiat->name = "Chinese Yuan (Renminbi) (CNY)";
                $auth->fiat->abbr = "CNY";
                $auth->fiat->symbol = "¥";
                $auth->fiat->save();
                break;
            case 'SEK':
                $auth->fiat->name = "Swedish Krona (SEK)";
                $auth->fiat->abbr = "SEK";
                $auth->fiat->symbol = "kr";
                $auth->fiat->save();
                break;
            case 'NZD':
                $auth->fiat->name = "New Zealand Dollar (NZD)";
                $auth->fiat->abbr = "NZD";
                $auth->fiat->symbol = "NZ$";
                $auth->fiat->save();
                break;
            default:
                $auth->fiat->name = "United States Dollar (USD)";
                $auth->fiat->abbr = "USD";
                $auth->fiat->symbol = "$";
                $auth->fiat->save();
                break;
        }


        return redirect()->back();
    }

    public function likeCartPlus($user, $created_at, $store, $product_created_at, Product $product, Request $request){
        
        $auth = auth()->user();

        $request->validate([
            'listing_action' => 'required|in:like,cart_plus',
        ]);

        $listingFavorited = $auth->favoriteListings->where('product_id', $product->id)->first();

        if ($request->listing_action === 'like' && $listingFavorited == null) {
            FavoriteListing::create(['user_id' => $auth->id, 'product_id' => $product->id]);
        }

        

        return redirect()->back();
    }
}
