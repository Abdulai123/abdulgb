<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\NotificationController;
use App\Models\Address;
use App\Models\NotificationType;
use MoneroIntegrations\MoneroPhp\walletRPC;
use App\Models\Withdraw;

class Wallet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdraw()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }



    // ... other model code ...

    public static function processTransactionDetails($address, $transactionDetails)
    {

        $confirmations = $transactionDetails['in'][0]['confirmations'];
        $status = $transactionDetails['in'][0]['locked'] ? 'Locked' : 'Unlocked';

        if ($confirmations >= 1 && $address->is_detected == 0) {
            self::sendReceivedNotification($address);
        }

        if ($confirmations >= 10  && $address->is_detected == 1 && $address->is_confirm == 0) {
            self::sendConfirmedNotification($address, $transactionDetails);
        }
    }

    public static function sendReceivedNotification($address)
    {
        $notificationType = NotificationType::where('action', 'received')->where('icon', 'deposit')->first();

        if ($notificationType) {
            $address->is_detected = 1;
            $address->save();

            NotificationController::create($address->wallet->user->id, null, $notificationType->id);
        }
    }

    public static function sendConfirmedNotification($address, $transactionDetails)
    {
        $notificationType = NotificationType::where('action', 'confirmed')->where('icon', 'deposit')->first();

        if ($notificationType) {
            $address->is_confirm = 1;
            $address->save();

            // Update user balance
            self::updateUserBalance($address, $transactionDetails['in'][0]['amount'], $transactionDetails);

            NotificationController::create($address->wallet->user->id, null, $notificationType->id);
        }
    }

    public static function updateUserBalance($address, $amountInAtomicUnits, $transactionDetails)
    {
        $amountInXMR = $amountInAtomicUnits / 1e12;
        $amountInUSD = $amountInXMR * session('xmr');

        $address->wallet->balance += $amountInUSD;
        $address->wallet->save();

        $deposit = new Deposit();
        $deposit->wallet_id = $address->wallet->id;
        $deposit->txid    = $transactionDetails['in'][0]['txid'];
        $deposit->amount = $amountInXMR;
        $deposit->save();
    }

    public static function balance()
    {

        $server = Server::where('type', 'wallet')->first();

        // Example Usage:
        $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, $server->password);
        $walletRPC->open_wallet($server->extra_user, $server->extra_pass);
        $get_balance = $walletRPC->get_balance();
        return  round((($get_balance['balance'] / 1e12) * session('xmr')), 2);
    }

    public static function ProccessOutGoinTransactions($transfers)
    {
        $confirmations = $transfers['out'];

        // Fetch all pending withdrawals
        $withdrawals = Withdraw::where('is_detected', 0)->orWhere('is_confirm', 0)->get();

        foreach ($withdrawals as $withdrawal) {
            foreach ($confirmations as $confirmation) {
                //Compare Monero wallet outgoing transaction address with withdrawal address
                if (!empty($confirmation['destinations']) && $withdrawal->address == $confirmation['destinations'][0]['address']) {

                   // Perform additional checks
                    if ($confirmation['confirmations'] >= 10 && !$withdrawal->is_confirm && $withdrawal->is_detected) {
                        // Update withdrawal status
                        $withdrawal->update([
                            'is_confirm' => 1,
                            'txid' => $confirmation['txid'],
                        ]);
                        $notificationType = NotificationType::where('action', 'completed')->where('icon', 'withdraw')->first();

                        if ($notificationType) {                
                            NotificationController::create($withdrawal->wallet->user->id, null, $notificationType->id);
                        }
                    } elseif ($confirmation['confirmations'] > 1 && !$withdrawal->is_confirm && !$withdrawal->is_detected) {
                        // Notify for pending confirmation
                        $withdrawal->update([
                            'is_detected' => 1,
                        ]);

                        $notificationType = NotificationType::where('action', 'in_progress')->where('icon', 'withdraw')->first();

                        if ($notificationType) {                
                            NotificationController::create($withdrawal->wallet->user->id, null, $notificationType->id);
                        }
                    }

                    //Break out of the inner loop as the address has been matched
                    break;
               }
            }
        }
    }
}
