<?php

namespace App\Models;

use App\Http\Controllers\NotificationController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function extraOption()
    {
        return $this->belongsTo(ExtraOption::class, 'extra_option_id');
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function escrow()
    {
        return $this->hasOne(Escrow::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }


    public static function cancelPendingOrdersOlderThan72Hours()
    {
        $ordersToCancel = self::where('status', 'pending')
            ->where('updated_at', '<', now()->subHours(72))
            ->get();

        foreach ($ordersToCancel as $order) {

            $escrowOrder = $order->escrow;

            if ($escrowOrder) {
                // Perform logic to return money to the user's wallet
                $order->user->wallet->balance += $escrowOrder->fiat_amount;
                $order->user->wallet->save();

                Order::sendNotifications($order, 'cancelled');
                $escrowOrder->status  = 'cancelled';
                $escrowOrder->save();
            }

            // update product sales and quantities
            $order->product->sold -= $order->quantity;
            $order->product->quantity += $order->quantity;
            $order->product->save();

            // Update store with sales
            $order->store->width_sales -= $order->quantity;
            $order->store->save();

            //update user totla_orders
            $order->user->total_orders -= $order->quantity;
            $order->user->save();
        }

        // Now, cancel the orders
        $ordersToCancel->each(function ($order) {
            $order->update(['status' => 'cancelled']);
        });
    }

    public static function completeSentDeliveredOrdersOlderThan72Hours()
    {
        $ordersToComplete = self::where(function ($query) {
            $query->where('status', 'sent')
                ->orWhere('status', 'delivered')
                ->orWhere('status', 'dispatched');
        })->where('updated_at', '<', now()->subHours(72))
            ->get();

        foreach ($ordersToComplete as $order) {
            if ($order->product->payment_type == 'Escrow') {
                // Check Escrow for the order and return money to the user's wallet
                $escrowOrder = $order->escrow;

                if ($escrowOrder) {
                    $cashback = MarketFunction::where('name', 'cashback')->first();
                    // if cashback still enable return 50% of escrow profit to user.
                    if ($cashback && $cashback->enable) {
                        $notificationType = NotificationType::where('action', 'received')->where('icon', 'cashback')->first();
                        NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
                    }

                    // Perform logic to return money to the store user's wallet
                    $storeBalance = $order->escrow->fiat_amount - ($order->escrow->fiat_amount * 0.05); // Deduct 5% fee
                    $order->store->user->wallet->balance += $storeBalance;
                    $order->store->user->wallet->save();

                    // update the user spent
                    $order->user->spent += $escrowOrder->fiat_amount;
                    $order->user->save();

                    Order::handleReferrals($order);

                    // notify the store and the user.
                    Order::sendNotifications($order, 'completed');
                    $escrowOrder->status  = 'released';
                    $escrowOrder->save();

                    // dedect the escrow fees
                }
            }
        }

        // Now, cancel the orders
        $ordersToComplete->each(function ($order) {
            $order->update(['status' => 'completed']);
        });
    }

    // Sends notifications to the user and store owner
    private static function sendNotifications(Order $order, $action)
    {

        $notificationType = NotificationType::where('action', $action)->where('icon', 'order')->first();
        if ($notificationType) {
            NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
            NotificationController::create($order->product->store->user_id, null, $notificationType->id, $order->id);
        }
    }


    // Handles referral logic and updates balances
    private static function handleReferrals(Order $order)
    {
        $referral = Referral::where('referred_user_id', $order->user->id)->first();
        $cashback = MarketFunction::where('name', 'cashback')->first();

        $escrowFees = $order->escrow->fiat_amount * 0.05;

        // Check if cashback is enabled
        if ($cashback && $cashback->enable) {
            // Save 50% of escrow fees to user's wallet
            $order->user->wallet->balance += $escrowFees * 0.5;
            $order->user->wallet->save();
            // Deduct 50% from the original escrow fees
            $escrowFees *= 0.5;
        }

        if ($referral) {
            // Check if escrowFees has already been reduced by 50% due to cashback
            if ($escrowFees === ($order->escrow->fiat_amount * 0.05 * 0.5)) {
                // Use the entire escrowFees for referral
                $referralAmount = $escrowFees;
                $escrowFees = 0;
            } else {
                // Calculate 5% of escrow, 50% to referrer
                $referralAmount = $escrowFees * 0.5;
                // Deduct 50% from the original escrow fees
                $escrowFees *= 0.5;
            }

            // Update the referrer's wallet balance
            $referral->user->wallet->balance += $referralAmount;
            $referral->user->wallet->save();

            // Update the referrer's referral balance
            $referral->balance += $referralAmount;
            $referral->save();
        }

        if ($escrowFees > 0) {
            // Add the remaining balance to the market wallet (logic not provided)
            $escrowWallet = User::where('private_name', 'escrow')->first();
            $escrowWallet->wallet->balance += $escrowFees;
            $escrowWallet->wallet->save();
        }
    }
}
