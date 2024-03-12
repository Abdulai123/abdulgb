<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\BlockStore;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\FavoriteStore;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\News;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Unauthorize;
use Illuminate\Support\Facades\Crypt;

class ShareController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index($actionName)
    {
        $store = auth()->user()->store;
        $unread_message_count = MessageStatus::where('user_id', auth()->user()->id)->where('is_read', 0)->count();
        if ($actionName == $store->store_name) {
            return View('Store.index', [
                'storeUser' => auth()->user(),
                'store' => $store,
                'action' => Null,
                'news'   => News::all(),
                'icon'  => GeneralController::encodeImages(),
                'categories' => Category::all(),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'unread_messages' => $unread_message_count,
            ]);
        }

        return abort(404);
    }

    public function ShowAction($actionName, $action)
    {
        $conversations = Conversation::all();
        $participants = Participant::where('user_id', auth()->user()->id)->get();

        return view('Store.index', [
            'store' => auth()->user()->store,
            'storeUser' => auth()->user(),
            'action' => $action,
            'news'   => News::all(),
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'categories' => Category::all(),
            'storeConversations'   => $participants,
            'conversations'   => $conversations,
        ]);
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
    public function store(StoreStoreRequest $request)
    {
        // return redirect()->back()->with('next-listing-pert', true)->with('parent_category_id', $request->parent_category_id);
    }

    /**
     * Display the specified resource.
     */
    public function show($name = null, Store $store)
    {
        return View('User.store', [
            'store' => $store,
            'name' => $name . ' Store',
            'user' => auth()->user(),
            'action' => Null,
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'categories' => Category::all(),
            'news' => News::where('created_at')->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'store_description'     => 'required|string|max:5000',
            'store_pgp'             => 'required|string|min:50|',
            'selling'               => 'required|string|min:1|max:3000',
            'ship_from'             => 'required|string|max:50',
            'ship_to'               => 'required|string|max:250',
            'status'                => 'required|string|min:3|max:16',
            'avatar'                => 'sometimes|image|mimes:jpeg,png,jpg|max:2000',
        ]);
        $store_id = Crypt::decrypt($request->store_id) ?? null;

        if (auth()->user()->store->id == $store_id) {
            if (auth()->user()->pin_code == $request->security_code) {

                $store = Store::find($store_id);
                $store->store_description = $request->store_description;
                $store->store_pgp         = $request->store_pgp;
                $store->selling           = $request->selling;
                $store->ship_from         = $request->ship_from;
                $store->ship_to           = $request->ship_to;
                $store->status            = $request->status;

                // Check if the submitted avatar is different from the current one
                if ($request->avater != null && $request->avater != $store->avatar) {
                    // Update the avatar only if it's different
                    $store->avatar = GeneralController::processAndStoreImage($request->avater, 'Upload_Images');
                }

                $store->save();

                return redirect()->back()->with('success', 'You have successfully updated your store information');
            }

            return redirect()->back()->with('error', 'Wrong secret code!');
        }
        return "Stop modifying the form, admin knows.";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }

    public function pgp($name, Store $store)
    {
        if ($name == $store->store_name) {
            return redirect()->back()->with('showpgp', true);
        }

        return abort(403);
    }

    public function reviews($name, Store $store)
    {
        if ($name == $store->store_name) {
            return view('User.storeReviews', [
                'name' => $name . ' Store',
                'user' => auth()->user(),
                'action' => Null,
                'store' => $store,
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
            ]);
        }

        return abort(403);
    }

    // Users actions on store dashboard in market
    public function checkAction(Request $request, $name, Store $store)
    {
        $user  = auth()->user();
        $storeFavorited   = $user->favoriteStores->where('store_id', $store->id)->first();
        $blockedStore     = $user->blockedStores->where('store_id', $store->id)->first();

        if ($name === $store->store_name) {
            if ($request->has('favorite_store') &&  $storeFavorited == null) {
                $favoriteStore  = new FavoriteStore();
                $favoriteStore->user_id = $user->id;
                $favoriteStore->store_id = $store->id;
                $favoriteStore->save();
                return redirect('/favorite/f_store')->with('success', 'You have add a store to your favorite stores!');;
            } elseif ($request->has('block_store') &&  $blockedStore  == null) {
                $blockStore  = new BlockStore();
                $blockStore->user_id = $user->id;
                $blockStore->store_id = $store->id;
                $blockStore->save();
                return redirect('/blocked/b_store')->with('success', 'You have add a store to your blocked stores!');
            }

            return redirect()->back();
        }

        return abort(404);
    }

    // store actions on orders
    public function storeAction(Request $request)
    {
        // Decrypt order ID
        $order_id = $request->order_id ? Crypt::decrypt($request->order_id) : null;
    

        // Check for a valid order ID
        if ($order_id === null) {
            return "Invalid order ID!";
        }
    
        if ($request->has('new_message')) {
           return redirect()->back()->with('new_message', true);
        }
        // Find the order
        $order = Order::find($order_id);
    
        // Check if the order exists
        if ($order === null) {
            return "Order not found!";
        }
    
        // Update order status based on the request
        $this->updateOrderStatus($order, $request);
    
        // Log unauthorized attempt if the 'completed' action is present
        $this->logUnauthorizedAttempt($request);
    
        // Notify user and store about the order status change
        $this->notifyOrderStatusChange($order);
    
        // Save the updated order
        $order->save();
    
        // Redirect back with a success message
        return redirect()->back()->with(
            'success',
            "You have successfully updated #" . strtotime($order->created_at) . " order status to " . $order->status
        );
    }
    
    private function updateOrderStatus(Order $order, Request $request)
    {
        // Update order status based on the current status and request
        switch ($order->status) {
            case 'pending':
                $this->updatePendingOrderStatus($order, $request);
                break;
            default:
                $this->updateNonPendingOrderStatus($order, $request);
                break;
        }
    }
    
    private function updatePendingOrderStatus(Order $order, Request $request)
    {
        // Update order status for pending orders
        switch (true) {
            case $request->has('cancel'):
                $order->status = 'cancelled';
                break;
            case $request->has('accept'):
                $order->status = 'processing';
                break;
        }
    }
    
    private function updateNonPendingOrderStatus(Order $order, Request $request)
    {
        // Update order status for non-pending orders
        switch (true) {
            case $request->has('dispute'):
                $order->status = 'dispute';
                $this->createDisputeConversation($order->id, null, $order->user_id, auth()->user()->id);
                break;
            case $request->has('sent'):
                $order->status = 'sent';
                break;
            case $request->has('shipped'):
                $order->status = 'shipped';
                break;
            case $request->has('delivered'):
                $order->status = 'delivered';
                break;
        }
    }
    
    private function logUnauthorizedAttempt(Request $request)
    {
        // Log unauthorized attempt if 'completed' action is present
        if ($request->has('completed')) {
            $unauthorize = new Unauthorize();
            $unauthorize->user_id = auth()->user()->id;
            $unauthorize->title = "Store Changing order status";
            $unauthorize->content = "Your request has been sent to admin to violate the website rules by editing the form to complete the order!";
            $unauthorize->url = $request->path();
            $unauthorize->role = auth()->user()->role;
            $unauthorize->save();
        }
    }
    
    private function notifyOrderStatusChange(Order $order)
    {
        // Notify user and store about the order status change
        $notificationType = NotificationType::where('action', $order->status == 'processing' ? 'accepted' : $order->status)->where('icon', 'order')->first();
        
        // Notify the user who placed the order
        NotificationController::create($order->user_id, null, $notificationType->id, $order->id);
    
        // Notify the store
        NotificationController::create(auth()->user()->id, null, $notificationType->id, $order->id);
    }
    
    private function createDisputeConversation($order_id, $escrow_id = null, $user_id,  $store_user_id)
    {

        // Create conversation
        $conversation = new Conversation();
        $conversation->topic = 'Dispute Started.';
        $conversation->save();

        // Participants
        foreach ([$user_id, $store_user_id] as $id) {
            $participant = new Participant();
            $participant->user_id = $id;
            $participant->conversation_id = $conversation->id;
            $participant->save();
        }

        // Create message
        $message = new Message();
        $message->content  = "This message was sent by auto mod system please user reply, the store has started a dispute.";
        $message->conversation_id  = $conversation->id;
        $message->message_type     = 'dispute';
        $message->save();

        foreach ([$user_id, $store_user_id] as $participant) {
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = $participant;
            $messageStatus->save();
        }

        $this->disputeOrder($order_id, $escrow_id, $conversation->id);
        // return redirect()->back()->with('success', 'Your message has been successfully sent to the store.');
    }

    private function disputeOrder($order_id, $escrow_id = null, $conversation_id){
        $dispute = new Dispute();
        $dispute->escrow_id = $escrow_id;
        $dispute->order_id  = $order_id;
        $dispute->conversation_id = $conversation_id;
        $dispute->save();
    }

    public function messageUser(Request $request, $user, $created_at, Order $order){

        if ($request->has('subject')) {
            // $request->validate([
            //     'subject' => 'required|min:5|max:100',
            //     'contents' => 'required|min:10|max:5000',
            // ]);

            return "Coming soon!!";
        }

        $store = auth()->user()->store;
        $unread_message_count = MessageStatus::where('user_id', auth()->user()->id)->where('is_read', 0)->count();
        if ($user == $order->user->public_name && $created_at == strtotime($order->created_at)) {
            return View('Store.index', [
                'storeUser' => auth()->user(),
                'store' => $store,
                'action' => 'messageUser',
                'order'  => $order,
                'news'   => News::all(),
                'icon'  => GeneralController::encodeImages(),
                'categories' => Category::all(),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'unread_messages' => $unread_message_count,
            ]);
        }



        return abort(404);
    }
}
