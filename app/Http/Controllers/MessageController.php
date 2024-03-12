<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Conversation;
use App\Models\MessageStatus;
use App\Models\Participant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Category;
use App\Models\Dispute;
use App\Models\Support;

class MessageController extends Controller
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
    public function create($name, $created_at, Store $store)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        if (strtotime($store->created_at) != $created_at) {
            return redirect()->back();
        }

        $user = auth()->user();
        if ($name === $store->store_name) {
            return view('User.createMessage', [
                'user' => $user,
                'icon'   => GeneralController::encodeImages(),
                'action' => NULL,
                'name'  => $name,
                'store' => $store,
            ]);
        }

        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $created_at, Conversation $conversation)
    {
        if ($request->has('new_message')) {
            return redirect()->back()->with('new_message', true);
        }

        if ($created_at == strtotime($conversation->created_at)) {
            return $this->createMessage($conversation->id, $request);
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }


    /**
     * Display the specified resource.
     */
    public function show($created_at, Conversation $conversation)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $user = auth()->user();
        $type = null;

        if (strtotime($conversation->created_at) == $created_at) {

            return view('User.displayMessages', [
                'user' => $user,
                'icon'   => GeneralController::encodeImages(),
                'action' => null,
                'name'  => null,
                'support_conversation' => $type != null ? $conversation : null,
                'normal_conversation' => $type != null ? null : $conversation,
            ]);
        }

        return abort(404);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }

    public function showMessages()
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $conversations = Conversation::all();
        $participants = Participant::where('user_id', auth()->user()->id)->get();

        $user = auth()->user();
        return view('User.message', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
            'userConversations'   => $participants,
            'conversations'   => $conversations,
        ]);
    }

    public function seniorModUser($user, Request $request, $created_at, Conversation $conversation)
    {
        $ticket = Support::where('conversation_id', $conversation->id)->first();
        if ($ticket != null && $ticket->status == 'closed') {
            return redirect()->back()->withErrors("This conversation has been closed");
        }

        if ($request->has('new_message')) {
            return redirect()->back()->with('new_message', true);
        }


        if ($ticket != null && $request->has('close_ticket')) {
            if ($ticket) {
                $ticket->status = 'closed';
                $ticket->save();

                return redirect()->back();
            } else {

                return redirect()->back();
            }
        }

        if ($created_at == strtotime($conversation->created_at)) {
            return $this->createMessage($conversation->id, $request);
        }
        return abort(404);
    }

    public function storeUser(Request $request, $name, $created_at, Conversation $conversation)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($request->has('new_message')) {
            return redirect()->back()->with('new_message', true);
        }

        if ($request->has('partial_refund')) {
            return redirect()->back()->with('partial_refund_form', true);
        }

        if ($request->has('contents') && $request->has('message_type')) {
            $this->createMessage($conversation->id, $request);
            return redirect()->back();
        }

        if ($request->has('request_staff')) {
            $order_id = Crypt::decrypt($request->order_id);
            $dispute  = Dispute::where('order_id', $order_id)->first();
            $dispute->mediator_request = 1;
            $dispute->save();

            return redirect()->back()->with('success', "The staff members has been notified please patient a while let a staff join the dispute process.");
        }
        if ($request->has('decline')) {
            $order_id = Crypt::decrypt($request->order_id);
            $dispute  = Dispute::where('order_id', $order_id)->first();
            $dispute->store_refund_reject = 1;
            $dispute->save();

            return redirect()->back();
        }

        if ($request->has('user_partial_percent') && $request->has('store_partial_percent')) {
            $order_id = Crypt::decrypt($request->order_id);
            $dispute  = Dispute::where('order_id', $order_id)->first();

            $request->validate(
                ['user_partial_percent' => 'required|integer|between:1,100'],
                ['store_partial_percent' => 'required|integer|between:1,100'],
            );

            if ($dispute->user_refund_accept != 0) {
                return redirect()->back()->with('percentage_error', 'Partial System Error: Stop Modifiying the dispute!!!');
            }
            $total = $request->user_partial_percent + $request->store_partial_percent;
            if ($total == 100) {

                $dispute->store_partial_percent = $request->store_partial_percent;
                $dispute->user_partial_percent = $request->user_partial_percent;
                $dispute->store_refund_accept = 1;
                $dispute->refund_initiated = 'Store';
                $dispute->status = 'Partial Refund';
                $dispute->save();

                return redirect()->back();
            } else {
                return redirect()->back()->with('percentage_error', 'Partial System Error: The total percentage for you and the store must be equal to 100%!!!');
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
            'message_type' => 'required|in:message,ticket,dispute,mass,staff',
        ]);

        $lastMessage = Message::where('conversation_id', $conversation_id)->first();


        if ($lastMessage) {
            if ($lastMessage->message_type == 'ticket') {
                $support = $user->supports->where('conversation_id', $conversation_id)->first();
                if ($support != null && $support->status == 'closed') {
                    return redirect()->back()->withErrors("This ticket closed has been closed!");
                }
            }
        }

        if($lastMessage && $lastMessage->message_type != $request->message_type){
            return redirect()->back()->withErrors("Stop modifying the from auto check will ban you!");
        }
        //Create message
        $message = new Message();
        $message->content  = $data['contents'];
        $message->user_id = $user->id;
        $message->conversation_id  = $conversation_id;
        $message->message_type     =  $lastMessage->message_type ?? $data['message_type'];
        $message->save();

        //Create Message status for participants
        $participants = Participant::where('conversation_id', $conversation_id)->where('is_hidden', 0)->get();
        foreach ($participants as $participant) {
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = $participant->user_id;
            $messageStatus->is_read    = $user->id == $participant->user_id ? 1 : 0;
            $messageStatus->save();
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    public function createModMailMessage(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/pgp/verify');
        }

        if ($request->has('subject')) {
            $request->validate([
                'subject' => 'required|min:5|max:100',
                'contents' => 'required|min:10|max:5000',
                'receiver' => 'required|string',
            ]);

            $user = auth()->user();

            if ($request->receiver == 'general') {
                $data = [];

                $users = User::where(function ($query) {
                    $query->where('role', 'admin')
                          ->orWhere('role', 'senior')
                          ->orWhere('role', 'junior');
                })->where('private_name', '!=', 'escrow')->get();
                
                foreach ($users as $loopUser) {
                    $data[] = $loopUser->id;
                }
                
            } else {
                $user_id = Crypt::decrypt($request->receiver);
                $data = [$user->id, $user_id];
            }

            // create a conversation
            $conversation = new Conversation();
            $conversation->topic = $request->subject;
            $conversation->save();

            //Create Message status for participants
            foreach ($data as $participant_user) {
                $participant = new Participant();
                $participant->user_id = $participant_user;
                $participant->conversation_id = $conversation->id;
                $participant->save();
            }

            // create message 
            $message = new Message();
            $message->content = $request->contents;
            $message->user_id = $user->id;
            $message->conversation_id = $conversation->id;
            $message->message_type = 'staff';
            $message->save();

            //Create Message status for participants
            $participants = Participant::where('conversation_id', $conversation->id)->get();


            foreach ($participants as $participant) {
                $messageStatus = new MessageStatus();
                $messageStatus->message_id = $message->id;
                $messageStatus->user_id    = $participant->user_id;
                $messageStatus->is_read    = $participant->user_id == $user->id ? 1 : 0;
                $messageStatus->save();
            }

            return redirect()->back()->with('success', 'Message sent successfully');
        }
    }
}
