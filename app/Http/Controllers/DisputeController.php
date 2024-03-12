<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Http\Requests\StoreDisputeRequest;
use App\Http\Requests\UpdateDisputeRequest;
use App\Models\Category;
use App\Models\MarketFunction;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\NotificationType;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Mime\MessageConverter;

class DisputeController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDisputeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dispute $dispute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dispute $dispute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisputeRequest $request, Dispute $dispute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dispute $dispute)
    {
        //
    }

    public function disputeShow(Request $request, $user, $created_at, Dispute $dispute)
    {

        if (auth()->user()->role == 'admin') {
            //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/whales/admin/pgp/verify');
            }
            if ($created_at == strtotime($dispute->created_at)) {
                return view('Admin.dispute', [
                    'parentCategories' => Category::whereNull('parent_category_id')->get(),
                    'subCategories' => Category::whereNotNull('parent_category_id')->get(),
                    'categories' => Category::all(),
                    'icon' => GeneralController::encodeImages(),
                    'upload_image' => GeneralController::encodeImages('Upload_Images'),
                    'product_image' => GeneralController::encodeImages('Product_Images'),
                    'order' => Order::where('id', $dispute->order_id)->first(),
                    'action' => 'order',
                    'user' => auth()->user(),
                    'dispute' => $dispute,
                ]);
            }
        } else {
            //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/staff/senior/pgp/verify');
            }
            if ($created_at == strtotime($dispute->created_at)) {
                return view('Senior.dispute', [
                    'parentCategories' => Category::whereNull('parent_category_id')->get(),
                    'subCategories' => Category::whereNotNull('parent_category_id')->get(),
                    'categories' => Category::all(),
                    'icon' => GeneralController::encodeImages(),
                    'upload_image' => GeneralController::encodeImages('Upload_Images'),
                    'product_image' => GeneralController::encodeImages('Product_Images'),
                    'order' => Order::where('id', $dispute->order_id)->first(),
                    'action' => 'order',
                    'user' => auth()->user(),
                    'dispute' => $dispute,
                ]);
            }
        }
    }


    public function disputeDo(Request $request, $user, $created_at, Dispute $dispute)
    {
        $user = auth()->user();
        if ($dispute->status == 'closed') {
            return redirect()->back()->withErrors("This dispute has been closed");
        }

        if ($created_at == strtotime($dispute->created_at)) {
            if ($request->has('join_dispute') && $dispute->mediator_id == null) {

                $participant = new Participant();
                $participant->user_id = $user->id;
                $participant->conversation_id = $dispute->conversation_id;
                $participant->save();

                $dispute->mediator_id = $user->id;
                $dispute->save();
                return redirect()->back();
            } elseif ($request->has('new_message') && $dispute->mediator_id == $user->id) {
                return redirect()->back()->with('new_message', true);
            } elseif ($request->has('contents') && $dispute->mediator_id == $user->id) {
                $this->createMessage($dispute->conversation_id, $request);
                return redirect()->back();
            } elseif ($request->has('finalize') && $request->has('user_partial_percent') && $request->has('store_partial_percent') && $dispute->mediator_id == $user->id) {

                $request->validate(
                    ['user_partial_percent' => 'required|integer|between:1,100'],
                    ['store_partial_percent' => 'required|integer|between:1,100'],
                );

                return $this->partialPercentage($request, $dispute->order);
            } elseif ($request->has('release_100_user') && $dispute->mediator_id == $user->id) {
                return $this->releaseOrderFunds($dispute->order, 'store');
            } elseif ($request->has('release_100_store') && $dispute->mediator_id == $user->id) {
                return $this->releaseOrderFunds($dispute->order, 'user');
            }
        }

        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    public function createMessage($conversation_id, $request)
    {
        $user = auth()->user();
        $data   = $request->validate([
            'contents' => 'required|string|min:2|max:5000',
            'message_type' => 'required|in:message,ticket,dispute',
        ]);

        $lastMessage = Message::where('conversation_id', $conversation_id)->first();


        if ($lastMessage) {
            if ($request->message_type == 'ticket' && $lastMessage['message_type'] == 'ticket') {
                $support = $user->supports->where('conversation_id', $conversation_id)->first();
                if ($support != null && $support->status != 'open') {
                    return redirect()->back();
                }
            } elseif ($request->message_type == 'ticket' && $lastMessage['message_type'] == 'ticket') {
                # code...
            } elseif ($request->message_type == 'ticket' && $lastMessage['message_type'] == 'ticket') {
                # code...
            }
        }
        //Create message
        $message = new Message();
        $message->content  = $data['contents'];
        $message->user_id = $user->id;
        $message->conversation_id  = $conversation_id;
        $message->message_type     = $data['message_type'];
        $message->save();

        //Create Message status for participants
        $participants = Participant::where('conversation_id', $conversation_id)->get();
        foreach ($participants as $participant) {
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = $participant->user_id;
            $messageStatus->is_read    = $user->id == $participant->user_id ? 1 : 0;
            $messageStatus->save();
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    private function partialPercentage(Request $request, Order $order)
    {
        // ... (Logic for accepting store funds in a dispute)
        $total = $request->user_partial_percent + $request->store_partial_percent;
        if ($total == 100) {

            $order->dispute->store_partial_percent = $request->store_partial_percent;
            $order->dispute->user_partial_percent = $request->user_partial_percent;

            if (auth()->user()->role == 'store') {
                $order->dispute->store_refund_accept = 1;
                $order->dispute->refund_initiated = 'Store';
            } elseif (auth()->user()->role == 'user') {
                $order->dispute->user_refund_accept = 1;
                $order->dispute->refund_initiated = 'User';
            }


            return $this->sharePartialFunds($order);
        } else {
            return redirect()->back()->with('percentage_error', 'Partial System Error: The total percentage for the store and the user must be equal to 100%!!!');
        }
    }

    private function sharePartialFunds(Order $order)
    {
        // ... (Logic for accepting store funds in a dispute)
        $this->sendNotifications($order, false, 'closed');
        $this->updateUserDetails($order,  $order->dispute->user_partial_percent);
        $this->updateStoreBalance($order, $order->dispute->store_partial_percent);
        $this->releaseEscrowFunds($order);
        $this->handlePartialDispute($order);

        $order->dispute->status = "closed";
        $order->dispute->save();

        return redirect()->back()->with('success', "You have successfully share the partial percentages between store and user, and dispute has been closed, thank you!");
    }

    // Sends notifications to the user and store owner
    private function sendNotifications(Order $order, $return_funds = false, $status = 'completed')
    {

        $cashback = MarketFunction::where('name', 'cashback')->first();


        // if cashback still enable return 50% of escrow profit to user.
        if ($return_funds == false && $cashback && $cashback->enable) {
            $notificationType = NotificationType::where('action', 'received')->where('icon', 'cashback')->first();
            NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
        }

        $notificationType = NotificationType::where('action', $status == 'completed' ? 'completed' : $status)->where('icon', $status == 'completed' ? 'order' : 'dispute')->first();

        if ($notificationType) {
            NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
            NotificationController::create($order->product->store->user_id, null, $notificationType->id, $order->id);
        }
    }


    // Updates user details related to the order
    private function updateUserDetails(Order $order, $accept = 0, $return_funds = false)
    {
        if ($accept > 0) {

            $userBalance = (($order->escrow->fiat_amount / 100) * $accept);

            // update user balance
            $order->user->wallet->balance += $userBalance;
            $order->user->wallet->save();
        } else {
            if ($return_funds == true) {
                // Increment the user's spent amount by the escrow fiat amount
                $order->user->wallet->balance += $order->escrow->fiat_amount;
                $order->user->wallet->save();
            } else {
                // Increment the user's spent amount by the escrow fiat amount
                $order->user->spent += $order->escrow->fiat_amount;
                $order->user->save();
            }
        }
    }

    // Updates the store balance
    private function updateStoreBalance(Order $order, $accept = 0, $return_funds = false)
    {
        if ($accept > 0) {
            // Calculate the total 5% escrow fees
            $escrowFees = ($order->escrow->fiat_amount / 100) * 5;

            // Calculate the amount to be deducted from the user's partial percent amount....
            $storeEscrowAmount = ($escrowFees / 100) * $accept;

            // Calculate the total store balance based on the user's partial percent amount...
            $storeBalance = ($order->escrow->fiat_amount / 100) * $accept;

            // update the user spent balance here...
            $order->user->spent += $storeBalance;
            $order->user->save();

            // Deduct the escrow fees from the store balance...
            $storeBalance -= $storeEscrowAmount;

            $order->store->user->wallet->balance += $storeBalance;
            $order->store->user->wallet->save();
            $this->handleReferralsAccept($order, false, $storeEscrowAmount);
        } else {
            if ($return_funds == false) {
                $storeBalance = $order->escrow->fiat_amount - ($order->escrow->fiat_amount * 0.05);
                $order->store->user->wallet->balance += $storeBalance;
                $order->store->user->wallet->save();
            }
        }
    }

    // Handles referral logic and updates balances 
    private function handleReferrals(Order $order, $return_funds = false)
    {
        $referral = Referral::where('referred_user_id', $order->user->id)->first();
        $cashback = MarketFunction::where('name', 'cashback')->first();

        $escrowFees = $order->escrow->fiat_amount * 0.05;

        // Check if cashback is enabled
        if ($return_funds == false && $cashback && $cashback->enable) {
            // Save 50% of escrow fees to user's wallet
            $order->user->wallet->balance += $escrowFees * 0.5;
            $order->user->wallet->save();
            // Deduct 50% from the original escrow fees
            $escrowFees *= 0.5;
        }

        if ($return_funds == false && $referral) {
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

        if ($return_funds == false && $escrowFees > 0) {
            // Add the remaining balance to the market wallet (logic not provided)
            $escrowWallet = User::where('private_name', 'escrow')->first();
            $escrowWallet->wallet->balance += $escrowFees;
            $escrowWallet->wallet->save();
        }
    }


    // Releases escrow funds to the store owner
    private function releaseEscrowFunds(Order $order)
    {
        if ($order->escrow) {
            $order->escrow->status = 'released';
            $order->escrow->save();
        }
    }

    private function handlePartialDispute($order)
    {
        if ($order->dispute->user_partial_percent < 50) {
            $order->dispute->winner = 'store';
            $order->dispute->status = 'closed';
            // Update user, store, and product stats for lost/won dispute (logic remains the same)
            $order->user->disputes_lost += 1;
            $order->user->save();

            $order->store->disputes_won += 1;
            $order->store->save();

            $order->product->disputes_won += 1;
            $order->product->save();
        } elseif ($order->dispute->store_partial_percent < 50) {
            $order->dispute->winner = 'user';
            $order->dispute->status = 'closed';
            // Update user, store, and product stats for lost/won dispute (logic remains the same)
            $order->user->disputes_won += 1;
            $order->user->save();

            $order->store->disputes_lost += 1;
            $order->store->save();

            $order->product->disputes_lost += 1;
            $order->product->save();
        } elseif ($order->dispute->store_partial_percent == 50) {
            $order->dispute->winner = 'both';
            $order->dispute->status = 'closed';
            // Update user, store, and product stats for lost/won dispute (logic remains the same)
            $order->user->disputes_won += 1;
            $order->user->save();

            $order->store->disputes_won += 1;
            $order->store->save();

            $order->product->disputes_won += 1;
            $order->product->save();
        }
    }

    private function releaseOrderFunds(Order $order, $role)
    {
        if ($role == 'store') {
            $this->sendNotifications($order, $return_funds = true, $order->status == 'dispute' ? 'closed' : 'completed');
            $this->updateUserDetails($order, 0, $return_funds = true);
            $this->handleReferrals($order, $return_funds = true);
            $this->updateStoreBalance($order, 0, $return_funds = true);
        } else {
            $this->sendNotifications($order, $return_funds = false, $order->status == 'dispute' ? 'closed' : 'completed');
            $this->updateUserDetails($order, 0, $return_funds = false);
            $this->handleReferrals($order, $return_funds = false);
            $this->updateStoreBalance($order, 0, $return_funds = false);
        }
        if ($order->status != 'dispute') {
            $this->updateOrderStatus($order);
        }
        $this->releaseEscrowFunds($order);
        $this->handleDisputeIfAny($order, $role);

        return redirect()->back()->with('success', 'Order completed successfully!');
    }

    // Handles dispute resolution if applicable
    private function handleDisputeIfAny(Order $order, $role)
    {

        if ($order->dispute && $order->dispute->status != 'closed') {

            if ($role == 'user') {
                # code...
                $order->dispute->status = 'closed';
                $order->dispute->winner = 'store';
                $order->dispute->save();

                // Update user, store, and product stats for lost/won dispute (logic remains the same)
                $order->user->disputes_lost += 1;
                $order->user->save();

                $order->store->disputes_won += 1;
                $order->store->save();

                $order->product->disputes_won += 1;
                $order->product->save();
            } elseif ($role == 'store') {
                # code...
                $order->dispute->status = 'closed';
                $order->dispute->winner = 'user';
                $order->dispute->save();

                // Update user, store, and product stats for lost/won dispute (logic remains the same)
                $order->user->disputes_won += 1;
                $order->user->save();

                $order->store->disputes_lost += 1;
                $order->store->save();

                $order->product->disputes_lost += 1;
                $order->product->save();
            }
        }
    }

    private function handleReferralsAccept(Order $order, $return_funds = false, $escrow_amount = null)
    {
        $referral = Referral::where('referred_user_id', $order->user->id)->first();
        $cashback = MarketFunction::where('name', 'cashback')->first();

        $escrowFees = $escrow_amount;

        // Check if cashback is enabled
        if ($return_funds == false && $cashback && $cashback->enable) {
            // Save 50% of escrow fees to user's wallet
            $order->user->wallet->balance += $escrowFees * 0.5;
            $order->user->wallet->save();
            // Deduct 50% from the original escrow fees
            $escrowFees *= 0.5;
        }

        if ($return_funds == false && $referral) {
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

        if ($return_funds == false && $escrowFees > 0) {
            // Add the remaining balance to the market wallet (logic not provided)
            $escrowWallet = User::where('private_name', 'escrow')->first();
            $escrowWallet->wallet->balance += $escrowFees;
            $escrowWallet->wallet->save();
        }
    }
}
