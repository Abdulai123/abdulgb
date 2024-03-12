<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Http\Requests\StoreSupportRequest;
use App\Http\Requests\UpdateSupportRequest;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends Controller
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
    public function create(Request $request)
    {
        if ($request->has('new_ticket')) {
            return redirect()->back()->with('new_ticket', true);
        }

        // Check the number of products added today
        $supportsAddedToday = auth()->user()->supports()->whereDate('created_at', Carbon::today())->count();
        // Limit the number of products a store can add in a day to 5
        $maxSupportsPerDay = 3;

        if ($supportsAddedToday >= $maxSupportsPerDay) {
            return redirect()->back()->withErrors('You have reached the maximum limit of support tickets that can be created in a day.');
        }

        if ($request->has('contents')) {
            $validatedData = $request->validate([
                'subject' => 'required|string|min:1|max:100',
                'contents' => 'required|string|min:1|max:5000',
                'message_type' => 'required|in:message,ticket,dispute',
                'captcha' => 'required|string|min:8|max:10'
            ]);

            // 'captcha'   => 'required|string|min:8|max:8',
            if ($request->captcha != session('captcha')) {
                return redirect()->back()->withErrors('Wrong captcha code, try again.');
            }

            // Create a new conversation
            $conversation = new Conversation();
            $conversation->topic = $validatedData['subject'];
            $conversation->save();


            $participant = new Participant();
            $participant->user_id = auth()->user()->id;
            $participant->conversation_id = $conversation->id;
            $participant->save();

            // Create a new message
            $message = new Message();
            $message->content = $validatedData['subject'];
            $message->user_id = auth()->user()->id; // Assuming a specific user ID for the message sender
            $message->conversation_id = $conversation->id;
            $message->message_type  = $validatedData['message_type'];
            $message->save();

            // Create a message status
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = auth()->user()->id;
            $messageStatus->is_read  = true;
            $messageStatus->save();

            // Craete the support 
            $support = new Support();
            $support->user_id = auth()->user()->id;
            $support->conversation_id = $conversation->id;
            $support->save();

            return redirect()->back()->with('success', 'You have successfully created a support tciket, It now pending.');
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Support $support)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupportRequest $request, Support $support)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        //
    }

    public function showTicket($user, $created_at)
    {
        $auth = auth()->user();
        return view('User.ticket', [
            'user' => $auth,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function storeCreate($store = null, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $store = auth()->user()->store;

        // Check the number of products added today
        $supportsAddedToday = $store->user->supports()->whereDate('created_at', Carbon::today())->count();
        // Limit the number of products a store can add in a day to 5
        $maxSupportsPerDay = 5;

        if ($supportsAddedToday >= $maxSupportsPerDay) {
            return redirect()->back()->withErrors('You have reached the maximum limit of support tickets that can be created in a day.');
        }

        if ($request->has('new_ticket')) {
            return redirect()->back()->with('new_ticket', true);
        }

        if ($request->has('contents')) {

            $validatedData = $request->validate([
                'subject' => 'required|string|min:1|max:100',
                'contents' => 'required|string|min:1|max:5000',
                'message_type' => 'required|in:ticket',
                
            ]);

            // 'captcha'   => 'required|string|min:8|max:8',
            // if ($request->captcha != session('captcha')) {
            //     return redirect()->back()->withErrors('Wrong captcha code, try again.');
            // }
            // Create a new conversation
            $conversation = new Conversation();
            $conversation->topic = $validatedData['subject'];
            $conversation->save();


            $participant = new Participant();
            $participant->user_id = auth()->user()->id;
            $participant->conversation_id = $conversation->id;
            $participant->save();

            // Create a new message
            $message = new Message();
            $message->content = $validatedData['subject'];
            $message->user_id = auth()->user()->id; // Assuming a specific user ID for the message sender
            $message->conversation_id = $conversation->id;
            $message->message_type  = $validatedData['message_type'];
            $message->save();

            // Create a message status
            $messageStatus = new MessageStatus();
            $messageStatus->message_id = $message->id;
            $messageStatus->user_id    = auth()->user()->id;
            $messageStatus->is_read  = true;
            $messageStatus->save();

            // Craete the support 
            $support = new Support();
            $support->user_id = auth()->user()->id;
            $support->conversation_id = $conversation->id;
            $support->save();

            return redirect()->back()->with('success', 'You have successfully created a support tciket, It now pending.');
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');

        return abort(404);
    }
}
