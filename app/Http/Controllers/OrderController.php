<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\MarketFunction;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\NotificationType;
use App\Models\Participant;
use App\Models\Referral;
use App\Models\Review;
use App\Models\Unauthorize;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrderController extends Controller
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
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($created_at, Order $order)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }
        $user   = auth()->user();
        if (strtotime($order->created_at) == $created_at) {

            if ($order->status == 'dispute') {
                $messages = $order->dispute->conversation->messages;

                foreach ($messages as $message) {
                    $status = $message->status->where('user_id', $user->id)->first();
                    $status->is_read  = 1;
                    $status->save();
                }
            }
            return view('User.orderViews', [
                'user' => $user,
                'parentCategories' => Category::whereNull('parent_category_id')->get(),
                'subCategories' => Category::whereNotNull('parent_category_id')->get(),
                'categories' => Category::all(),
                'icon' => GeneralController::encodeImages(),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'order' => $order,
                'name'  => 'order',
                'action' => null,
            ]);
        }

        return abort(404);
    }


    public function showStoreOrder($store, $created_at, Order $order)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $store = auth()->user()->store;

        if (strtotime($order->created_at) == $created_at) {

            if ($order->status == 'dispute') {
                $messages = $order->dispute->conversation->messages;

                foreach ($messages as $message) {
                    $status = $message->status->where('user_id', auth()->user()->id)->first();
                    $status->is_read  = 1;
                    $status->save();
                }
            }

            return view('Store.orderView', [
                'storeUser' => auth()->user(),
                'store' => $store,
                'parentCategories' => Category::whereNull('parent_category_id')->get(),
                'subCategories' => Category::whereNotNull('parent_category_id')->get(),
                'categories' => Category::all(),
                'icon' => GeneralController::encodeImages(),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'order' => $order,
                'action' => 'order',
            ]);
        }

        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function StoreUpdate(UpdateOrderRequest $request, $store = NULL, $created_at, Order $order)
    {
        if (strtotime($order->created_at) == $created_at) {
            return $this->orderChanges($request,  $order);
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    public function update(UpdateOrderRequest $request, $created_at, Order $order)
    {
        if (strtotime($order->created_at) == $created_at) {
            return $this->orderChanges($request,  $order);
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }


    private function createMessage($data, $conversation_id)
    {
        $user  = auth()->user();
        //Create message
        $message = new Message();
        $message->content  = $data['contents'];
        $message->user_id = $user->id;
        $message->conversation_id  = $conversation_id;
        $message->message_type     = 'dispute';
        $message->save();

        //Create Message status for participants
        $participants = Participant::where('conversation_id', $conversation_id)->where('is_hidden', 0)->get();
        foreach ($participants as $participant) {
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = $participant->user_id;
            if ($participant->user_id == $user->id) {
                $messageStatus->is_read = 1;
            } else {
                $messageStatus->is_read =  0;
            }

            $messageStatus->save();
        }

        foreach (Message::where('conversation_id', $conversation_id)->get() as $message) {
            foreach ($message->status->where('user_id', $user->id) as $messageStatus) {
                $messageStatus->is_read = 1;
                $messageStatus->save();
            }
        }

        return;
    }

    public function addStoreNote(Request $request, $store, $created_at, Order $order)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($store == $order->store->store_name  && $created_at == strtotime($order->created_at)) {
            $request->validate(['store_note' => 'required|min:3|max:5000']);
            $order->store_notes = $request->store_note;
            $order->save();
            return redirect()->back()->with('success', 'You have successfully update the store note for this order.');
        }
    }

    public static function initiateOrder($user, Cart $cart)
    {
        $order = new Order([
            'user_id' => $user->id,
            'product_id' => $cart->product_id,
            'store_id' => $cart->product->store_id,
            'extra_option_id' => $cart->extraShipping->id,
            'quantity' => $cart->quantity,
            'extra_amount' => $cart->extraShipping->cost,
            'cost_per_item' => $cart->product->price,
            'discount' => $cart->discount,
            'shipping_address' => $cart->note,
        ]);
        $order->save();

        // Check if the product is set for auto dispatch
        if ($cart->product->auto_delivery_content && $cart->product->product_type == 'digital') {
            $notificationType = NotificationType::where('action', 'dispatched')->where('icon', 'order')->first();

            NotificationController::create($user->id, null, $notificationType->id, $order->id);
            NotificationController::create($cart->product->store->user_id, null, $notificationType->id, $order->id);

            $order->status = 'dispatched';
            $order->store_notes = $order->product->auto_delivery_content;
            $order->save();
        }

        $notificationType = NotificationType::where('action', 'created')->where('icon', 'order')->first();

        NotificationController::create($user->id, null, $notificationType->id, $order->id);
        NotificationController::create($cart->product->store->user_id, null, $notificationType->id, $order->id);

        return $order;
    }

    private function orderChanges(UpdateOrderRequest $request, Order $order)
    {

        if ($request->has('cancel')) {
            if ($order->status != 'pending') {
                $this->logUnauthorizedAttempt($request, 'order not pending and try to cancelled.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            return $this->cancelOrder($request, $order);
        } elseif ($request->has('extend_time')) {
            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }
            if (auth()->user()->role == 'store') {
                $this->logUnauthorizedAttempt($request, 'Store modify form to release order funds.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->status != 'pending' && $order->status != 'dispatched' && $order->status != 'sent' && $order->status != 'delivered') {
                $this->logUnauthorizedAttempt($request, 'order is not pending and try to extend time.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            $order->updated_at = now();
            $order->save();

            return redirect()->back()->with('success', 'This order auto time system has been extended to another 3 days.');
        } elseif ($request->has('dispute')) {

            if ($order->status == 'pending' && $order->status == 'completed' && $order->status == 'dispute') {
                $this->logUnauthorizedAttempt($request, 'order is completed, disputed or pending and try to dispute.');
                return redirect()->back();
            }
            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back();
            }

            return $this->disputeOrder($request, $order);
        } elseif ($request->has('decline')) {

            if ($order->status != 'dispute' || $order->dispute->refund_initiated == 'none') {
                $this->logUnauthorizedAttempt($request, 'Order is not been dispute or it not initiated by none.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->dispute->store_refund_reject != 0 || $order->dispute->store_refund_reject != 0) {
                $this->logUnauthorizedAttempt($request, 'dispute has been rejected and still he is modifying the form.');
                return redirect()->back()->withErrors("Stop modifying the form, partial funds has already been rejected");
            }

            return $this->decline($request, $order);
        } elseif ($request->has('dispute_form')) {

            if ($order->dispute == null) {
                $this->logUnauthorizedAttempt($request, 'order dispute doesnt exist and try to submit form.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->dispute->status == 'closed') {
                $this->logUnauthorizedAttempt($request, 'order dispute has been closed and try tp submit form.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            return $this->createDisputeMessage($request, $order);
        } elseif ($request->has('release')) {
            if ($order->status == 'dispute' || $order->status == 'completed') {
                $this->logUnauthorizedAttempt($request, 'order try to release funds for disputed or completed order.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if (auth()->user()->role == 'store') {
                $this->logUnauthorizedAttempt($request, 'Store modify form to release order funds.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }
            return $this->releaseOrderFunds($request, $order);
        } elseif ($request->has('release_100')) {
            if ($order->dispute->winner != 'none' || $order->dispute->refund_initiated != 'none') {
                $this->logUnauthorizedAttempt($request, 'order try to release funds for disputed or completed order.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }
            if ($order->dispute->user_refund_reject == 1) {
                $this->logUnauthorizedAttempt($request, 'User rejected the store funds but still he modify the from.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }
            if ($order->dispute->store_refund_reject == 1) {
                $this->logUnauthorizedAttempt($request, 'Store rejected the user funds but still he modify the from.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            return $this->releaseOrderFunds($request, $order);
        } elseif ($request->has('new_message')) {
            if ($order->status != 'dispute' || $order->dispute->status == 'closed') {
                $this->logUnauthorizedAttempt($request, 'Order dispute closed or there is no dispute but try to send message.');
            }

            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back();
            }

            return redirect()->back()->with('new_message', true);
        } elseif ($request->has('partial_refund')) {
            if ($order->dispute === null || $order->dispute->status == 'Partial Refund') {
                $this->logUnauthorizedAttempt($request, 'order try to start a Partial Refund while dispute is in Partial Refund.');
                return redirect()->back();
            }

            if ($order->dispute === null || $order->dispute->status == 'closed') {
                $this->logUnauthorizedAttempt($request, 'order try to start a Partial Refund while dispute is closed.');
                return redirect()->back();
            }

            return $this->PartialRefundRequest($request, $order);
        } elseif ($request->has('review_form')) {

            if ($order->status != 'completed' && $order->product->payment_type != "FE") {
                $this->logUnauthorizedAttempt($request, 'order review not valid order not completed or fe.');
                return redirect()->back();
            }

            if (now()->diffInDays($order->updated_at) > 5) {
                $this->logUnauthorizedAttempt($request, 'order review not valid order updated more than 5 days a go.');
                return redirect()->back();
            }

            return $this->createReview($request, $order);
        } elseif ($request->has('request_staff')) {

            if ($order->dispute == null && $order->dispute->mediator_request == 1) {
                $this->logUnauthorizedAttempt($request, 'order try to request mode while mod has been requested.');
                return redirect()->back();
            }

            if ($order->product->payment_type == 'FE') {
                $this->logUnauthorizedAttempt($request, 'This order payment type is not Escrow and user try to cancel it');
                return redirect()->back();
            }

            return $this->requestStaffMediation($request, $order);
        } elseif ($request->has('accept_partial_amount')) {

            if ($order->dispute->refund_initiated == 'none') {
                $this->logUnauthorizedAttempt($request, 'order try to accept store funds while refund initiated by user or none.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if (auth()->user()->role == 'store' && $order->dispute->refund_initiated == 'Store') {
                $this->logUnauthorizedAttempt($request, 'order try to accept store funds while refund initiated by store.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if (auth()->user()->role == 'user' && $order->dispute->refund_initiated == 'User') {
                $this->logUnauthorizedAttempt($request, 'order try to accept user funds while refund initiated by user.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            if ($order->dispute->status == 'closed') {
                $this->logUnauthorizedAttempt($request, 'order try to accept user funds while dispute has been closed.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            ///return dd($request);
            return $this->acceptPartialFunds($request, $order);
        } elseif ($request->has('user_partial_percent') && $request->has('store_partial_percent')) {

            if (($order->dispute->refund_initiated != 'none')) {
                $this->logUnauthorizedAttempt($request, 'order try to initiate partial percentage while it been iniataited.');
                return redirect()->back()->withErrors("Store modifying the form, auto check has notify admin and mod for your action.");
            }

            $request->validate(
                ['user_partial_percent' => 'required|integer|between:1,100'],
                ['store_partial_percent' => 'required|integer|between:1,100'],
            );

            return $this->partialPercentage($request, $order);
        } else {
            $this->logUnauthorizedAttempt($request, 'order 404 something went wrong.');
            return abort(404);
        }
    }

    private function cancelOrder(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for canceling the order)
        $notificationType = NotificationType::where('action', 'cancelled')->where('icon', 'order')->first();

        if ($notificationType) {
            NotificationController::create(null, null, $notificationType->id, $order->id);
            NotificationController::create($order->product->store->user_id, null, $notificationType->id, $order->id);
        }
        $order->status = 'cancelled';
        $order->save();

        $this->updateStoreSales($order);
        $this->updateUserOrders($order);
        $this->updateProductSales($order);

        return redirect()->back();
    }

    private function decline(UpdateOrderRequest $request, Order $order)
    {
        if (auth()->user()->role == 'store') {
            $order->dispute->store_refund_reject = 1;
        } else {
            $order->dispute->user_refund_reject = 1;
        }
        $order->dispute->save();
        return redirect()->back();
    }

    private function disputeOrder(UpdateOrderRequest $request, Order $order)
    {
        $user = auth()->user();
        // ... (Logic for disputing the order)
        $notificationType = NotificationType::where('action', 'dispute')->where('icon', 'dispute')->first();

        if ($notificationType) {
            NotificationController::create(null, null, $notificationType->id, $order->id);
            NotificationController::create($order->product->store->user_id, null, $notificationType->id, $order->id);
        }

        $order->status = 'dispute';
        $order->save();
        // create conversation for dispute
        $conversation = new Conversation();
        $conversation->topic = "New dispute created";
        $conversation->save();

        // Particippants
        $participants = new Participant();
        $participants->user_id = $user->id;
        $participants->conversation_id = $conversation->id;
        $participants->save();

        $participants = new Participant();
        $participants->user_id = $order->product->store->user_id;
        $participants->conversation_id = $conversation->id;
        $participants->save();

        // create the dispute now
        $dispute = new Dispute();
        $dispute->order_id = $order->id;
        $dispute->escrow_id = $order->escrow != null ? $order->escrow->id : null;
        $dispute->conversation_id = $conversation->id;
        $dispute->save();

        // Create message
        $message = new Message();
        $message->content  = "This message was sent by auto mod system please store reply, the user has started a dispute.";
        $message->conversation_id  = $conversation->id;
        $message->message_type     = 'dispute';
        $message->save();

        foreach ([$user->id, $order->product->store->user_id] as $participant) {
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = $participant;
            $messageStatus->is_read    = $participant == $user->id ? 1 : 0;
            $messageStatus->save();
        }

        return redirect()->back();
    }

    private function createDisputeMessage(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for creating a dispute message)
        $data = $request->validate([
            'contents' => 'required|string|min:2|max:1000',
            'captcha'  => 'required|string|min:8|max:8',
        ]);

        if ($request->captcha != session('captcha')) {
            return redirect()->back()->withErrors('Wrong captcha code, try again.');
        }

        $dispute = Dispute::where('order_id', $order->id)->first();
        $this->createMessage($data, $dispute->conversation_id);
        return redirect()->back();
    }

    private function PartialRefundRequest(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for handling partial refund request)
        return redirect()->back()->with('partial_refund_form', true);
    }

    private function createReview(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for creating a review)

        $user = auth()->user();

        $request->validate([
            'review_type' => 'required|in:positive,neutral,negative',
            'comment'    => 'required|string|min:3|max:2000',
            'communication_rating' => 'required|between:1,5|integer',
            'product_rating'  => 'required|between:1,5|integer',
            'shipping_speed_rating'  => 'required|between:1,5|integer',
            'price_rating'  => 'required|between:1,5|integer'
        ]);

        if ($order->review == null) {
            $review = new Review();
        } else if ($order->review != null && now()->diffInDays($order->updated_at) <= 5) {
            $review = Review::where('order_id', $order->id)->first();
        }
        $review->user_id = $user->id;
        $review->product_id = $order->product_id;
        $review->store_id = $order->store_id;
        $review->communication_rating = $request->communication_rating;
        $review->product_rating = $request->product_rating;
        $review->shipping_speed_rating = $request->shipping_speed_rating;
        $review->price_rating = $request->price_rating;
        $review->feedback = $request->review_type;
        $review->comment  = $request->comment;
        $review->order_id = $order->id;
        $review->save();

        return redirect()->back();
    }

    private function requestStaffMediation(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for requesting staff mediation)
        $order->dispute->mediator_request = 1;
        $order->dispute->save();

        return redirect()->back()->with('success', "The staff members has been notified please patient a while let a staff join the dispute process.");
    }

    private function acceptPartialFunds(UpdateOrderRequest $request, Order $order)
    {
        // ... (Logic for accepting store funds in a dispute)
        $this->sendNotifications($order, false, 'closed');
        $this->updateUserDetails($order,  $order->dispute->user_partial_percent);
        $this->updateStoreBalance($order, $order->dispute->store_partial_percent);
        $this->releaseEscrowFunds($order);
        $this->handlePartialDispute($order);

        $order->dispute->status = "closed";
        $order->dispute->save();

        return redirect()->back()->with('success', "You have accepted the partial percentage, and dispute has been closed, thank you!");
    }

    private function partialPercentage(UpdateOrderRequest $request, Order $order)
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
            $order->dispute->status = 'Partial Refund';
            $order->dispute->save();

            return redirect()->back();
        } else {
        GeneralController::logUnauthorized($request, '404', '404 page returned');
            return redirect()->back()->with('percentage_error', 'Partial System Error: The total percentage for you and the store must be equal to 100%!!!');
        }
    }

    private function logUnauthorizedAttempt(Request $request, $title)
    {
        // Log unauthorized attempt if 'completed' action is present
        $unauthorize = new Unauthorize();
        $unauthorize->user_id = auth()->user()->id;
        $unauthorize->title = $title;
        $unauthorize->content = "Your request has been sent to admin to violate the website rules by editing the form to complete the order!";
        $unauthorize->url = $request->path();
        $unauthorize->role = auth()->user()->role;
        $unauthorize->save();
    }

    private function releaseOrderFunds(UpdateOrderRequest $request, Order $order)
    {
        $role = auth()->user()->role;

        if ($role == 'store') {
            $this->sendNotifications($order, $return_funds = true,  $order->status == 'dispute' ? 'closed' : 'completed');
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
        $this->handleDisputeIfAny($order);

        return redirect()->back()->with('success', 'Order completed successfully!');
    }

    // ... (Define the new smaller methods below)

    // Updates the order status to 'completed'
    private function updateOrderStatus(Order $order)
    {
        if ($order->status != 'dispute') {
            $order->status = 'completed';
            $order->save();
        }
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

        $notificationType = NotificationType::where('action', $status)->where('icon', $status == 'closed' ? 'dispute' : 'order')->first();

        if ($notificationType) {
            NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
            NotificationController::create($order->product->store->user_id, null, $notificationType->id, $order->id);
        }
    }

    // Updates product sales information
    private function updateProductSales(Order $order)
    {
        $order->product->sold -= $order->quantity;
        $order->product->quantity += $order->quantity;
        $order->product->save();
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

    // Releases escrow funds to the store owner
    private function releaseEscrowFunds(Order $order)
    {
        if ($order->escrow) {
            $order->escrow->status = 'released';
            $order->escrow->save();
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

    // Updates store sales information
    private function updateStoreSales(Order $order)
    {
        $order->store->width_sales -= $order->quantity;
        $order->store->save();
    }

    // Updates store sales information
    private function updateUserOrders(Order $order)
    {
        $order->user->total_orders -= $order->quantity;
        $order->user->save();

        $order->user->wallet->balance += $order->escrow->fiat_amount;
        $order->user->wallet->save();
    }

    // Handles dispute resolution if applicable
    private function handleDisputeIfAny(Order $order)
    {
        $role = auth()->user()->role;

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

        if (auth()->user()->role == 'store') {
            $order->dispute->store_refund_accept = 1;
        } else {
            $order->dispute->user_refund_accept = 1;
        }
    }
}
