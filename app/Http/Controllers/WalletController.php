<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Address;
use App\Models\MarketFunction;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Server;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MoneroIntegrations\MoneroPhp;
use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($user)
    {
        if (MarketFunction::where('name', 'deposit')->first()->enable == 0) {
            return redirect()->back()->withErrors("All deposits is currently disable by admin or mods, open a support ticket!");
        }

        $server = Server::where('type', 'wallet')->first();

        if ($server == null) {
            return abort(401)    ;     
        }
        // Example Usage:
        $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, $server->password);
        $walletRPC->open_wallet($server->extra_user, $server->extra_pass);

        if ($user == auth()->user()->public_name) {
            $user = auth()->user();

            $latest_address = $user->wallet->address->last();

            if ($latest_address == null) {
                $create_address = $walletRPC->create_address(0, $user->public_name); // Create a subaddress on account 0
                $new_address = new Address();
                $new_address->wallet_id = $user->wallet->id;
                $new_address->address = $create_address['address'];
                $new_address->save();
                $latest_address = $new_address;
            }

            if ($latest_address->expired === 1) {
                $create_address = $walletRPC->create_address(0, $user->public_name); // Create a subaddress on account 0

                $new_address = new Address();
                $new_address->wallet_id = $user->wallet->id;
                $new_address->address = $create_address['address'];
                $new_address->save();
                $latest_address = $new_address;
            }


            $from = [0, 128, 255];
            $to = [11, 57, 150];

            $qrCode = QrCode::size(200)
                ->style('dot')
                ->eye('circle')
                ->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')
                ->margin(1)
                ->generate($latest_address->address);

            return redirect()->back()->with('address', $latest_address->address)->with('qrcode', $qrCode);
        }

        
        return abort(404, "This dead url has been sent to admin");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public static function show()
    {

        if (MarketFunction::where('name', 'deposit')->first()->enable == 0) {
            return  [
                'message' => 'All despoit is disable by admin or mods, check back later!',
            ];
        }

        $server = Server::where('type', 'wallet')->first();

        if ($server == null) {
            return abort(401)    ;     
        }
        // Example Usage:
        $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, $server->password);
        $walletRPC->open_wallet($server->extra_user, $server->extra_pass);

        $user = auth()->user();
        $address = $user->wallet->address->where('expired', 0)->first();

        if ($address != null) {
            $add_index = $walletRPC->get_address_index($address->address);
        } else {
            $add_index = false;
        }

        if ($add_index) {
            // Now that $address is set, use it in get_transfers
            $get_transfers = $walletRPC->get_transfers(
                'in',
                $add_index['index']['major'],
                [$add_index['index']['minor']]
            );

            // return dd($get_transfers);
            if (!empty($get_transfers)) {
                $transactionDetails = $get_transfers['in'][0]; // Assuming the relevant information is at index 0

                $amountInAtomicUnits = $transactionDetails['amount'];
                $amountInXMR = ($amountInAtomicUnits / 1e12);
                $amountInXMRFormatted = number_format($amountInXMR, 4);

                $confirmations = $transactionDetails['confirmations'];
                $txid = $transactionDetails['txid'];
                $status = $transactionDetails['locked'] ? 'Locked' : 'Unlocked';
                $address = $transactionDetails['address'];

                // Determine the transaction status
                if ($confirmations >= 10 && $status === 'Unlocked') {
                    $transactionStatus = 'Confirmed';
                } elseif ($confirmations >= 1) {

                    $transactionStatus = 'Pending';
                } else {
                    $transactionStatus = 'Failed';
                }

                // Format the date as needed
                $date = date('Y-m-d H:i:s', $transactionDetails['timestamp']);

                // Return transaction details as an associative array
                return [
                    'address' => $address,
                    'txid' => $txid,
                    'amount' => $amountInXMRFormatted,
                    'confirmations' => $confirmations,
                    'status' => $transactionStatus,
                    'date' => $date,
                ];
            } else {
                // Return a default message or an empty array when there are no incoming transfers
                return [
                    'message' => 'No incoming transaction detected yet...',
                    'address' => $address->value,
                    'date' => '',
                ];
            }
        } else {
            return [
                'message' => 'null',
            ];
        }
    }



    public function withdraw(Request $request)
    {

        if (MarketFunction::where('name', 'withdraw')->first()->enable == 0) {
            return redirect()->back()->withErrors("All withdrawals is currently disable by admin or mods, open a support ticket!");
        }


        $request->validate([
            'address' => 'required|string|regex:/^[0-9a-zA-Z]{95}$/',
            'amount' => 'required|numeric|min:0|regex:/^\d{0,12}(\.\d{0,2})?$/',
            'pin' => 'required|numeric|digits:6', // Assuming a 4-digit pin
            
        ]);
        // Retrieve the user's current wallet balance
        $user = auth()->user();

        if ($user->role != 'admin' && $user->role != 'senior') {
            $request->validate([
                'captcha' => 'required|string|min:8|max:8', // Assuming a captcha with exactly 8 characters
            ]);

            if ($request->captcha != session('captcha')) {
                // wrong captcha
                return redirect()->back()->withErrors("Wrong captcha code...");
            }
            
        }
        // Check if the requested withdrawal amount is greater than the balance
        if ($request->amount > $user->wallet->balance) {
            // If the requested amount is greater, redirect back with an error message
            return redirect()->back()->withErrors("You do not have the amount specified in your wallet balance.");
        }

        if ($request->pin != $user->pin_code) {
            // Wrong secret code
            return redirect()->back()->withErrors("Wrong secret code code...");
        }


        $server = Server::where('type', 'wallet')->first();

        if ($server == null) {
            GeneralController::logUnauthorized($request, 'wallet server problem', 'Wallet server return null');
            return abort(401);
        }

        $amount_xmr = ($request->amount / session('xmr'));
        $withdraw = new Withdraw();
        $withdraw->wallet_id = $user->wallet->id;
        $withdraw->address  = $request->address;
        $withdraw->amount   = round($amount_xmr, 4);
        $withdraw->save();

        $user->wallet->balance -= $request->amount;
        $user->wallet->save();

        // Example Usage:
        $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, $server->password);
        $walletRPC->open_wallet($server->extra_user, $server->extra_pass);

        $walletRPC->transfer($amount_xmr, $request->address);

        return redirect()->back()->with('success', "Withdrawals initiated. Please wait 5 minutes and refresh. Look for notifications. If you encounter any issues, please open a support ticket.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }


    // public function test()
    // {
    //     $server = Server::where('type', 'wallet')->first();

    // //     // Example Usage:
    //     $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, '3fi8koYOuwU1KumYwpCSJg==');
    //     $walletRPC->open_wallet($server->extra_user, $server->extra_pass);

    //    $server = Server::where('type', 'wallet')->first();

    //     // Example Usage:
    //     $get_balance = $walletRPC->get_balance();

        // $get_address = $walletRPC->get_address();
        // $get_accounts = $walletRPC->get_accounts();
        // $get_balance = $walletRPC->get_balance();
        // $get_height = $walletRPC->get_height();

        //$create_address = $walletRPC->create_address(0, 'This is an example subaddress label'); // Create a subaddress on account 0
        // $tag_accounts = $walletRPC->tag_accounts([0], 'This is an example account tag');

        // $transfer = $walletRPC->transfer(1, '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn'); // First account generated from mnemonic 'gang dying lipstick wonders howls begun uptight humid thirsty irony adept umpire dusted update grunt water iceberg timber aloof fudge rift clue umpire venomous thirsty'
        // $transfer = $walletRPC->transfer(['address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'amount' => 1, 'priority' => 1]); // Passing parameters in as array
        // $transfer = $walletRPC->transfer(['destinations' => ['amount' => 1, 'address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'amount' => 2, 'address' => 'BhASuWq4HcBL1KAwt4wMBDhkpwseFe6pNaq5DWQnMwjBaFL8isMZzcEfcF7x6Vqgz9EBY66g5UBrueRFLCESojoaHaTPsjh'], 'priority' => 1]); // Multiple payments in one transaction
        // $sweep_all = $walletRPC->sweep_all('9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn');
        // $sweep_all = $walletRPC->sweep_all(['address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'priority' => 1]);

        //$adr = auth()->user()->wallet->address->where('expired', 0)->address;
       // $get_transfers = $walletRPC->get_transfers('out', true);

        // if (!empty($get_transfers['in'])) {
        //     $transactionDetails = $get_transfers['in'][0];

        //     $amountInAtomicUnits = $transactionDetails['amount'];
        //     $amountInXMR = $amountInAtomicUnits / 1e12;
        //     $amountInXMRFormatted = number_format($amountInXMR, 4);

        //     $confirmations = $transactionDetails['confirmations'];
        //     $txid = $transactionDetails['txid'];
        //     $status = $transactionDetails['locked'] ? 'Locked' : 'Unlocked';

        //     return  dd($walletRPC->get_address_index('83G6E8N1XAM3Em5nGAnpxs14diY9QHC1Dg6KXCVGqFzZ5p3Jiz4DhDheJ5EBcwfACG2jRW1HysWmwYHRQrdbqTyfKFx822X')); //"Transaction ID: $txid\nAmount: $amountInXMRFormatted XMR\nConfirmations: $confirmations\nStatus: $status";
        // } else {
        // Handle the case when there are no incoming transfers
        //return  '$' . round((($get_balance['balance'] / 1e12) * session('xmr')), 2);
//         return  $get_balance;
//         // }

        
//    }
}
