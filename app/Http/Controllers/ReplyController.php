<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ReplyController extends Controller
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
    public function create(Request $request, $store, $created_at, Review $review)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($store == $review->store->store_name && $created_at == strtotime($review->created_at)) {
            if ($request->has('new_reply') && $request->has('reply_text') == null) {
                return redirect()->back()->with('new_reply', true)->with('review_id', $review->id);
            }

            if ($request->has('new_reply') && $request->has('reply_text')) {
                $request->validate(['reply_text'   => 'required|min:5|max:5000',]);

                $reply = new Reply();
                $reply->review_id = $review->id;
                $reply->reply     = $request->reply_text;
                $reply->save();

                return redirect()->back()->with('new_reply', false)->with('review_id', $review->id);
            }


            if ($request->has('edit') && $request->has('reply_text')  == null) {
                return redirect()->back()->with('edit', true)->with('review_id', $review->id);
            }

            if ($request->has('edit') && $request->has('reply_text')) {
                $request->validate([
                    'reply_text'   => 'required|min:5|max:5000',
                    'reply_id'     => 'required|min:32',
                ]);

                $reply = Reply::find(Crypt::decrypt($request->reply_id));

                if (now()->diffInDays($reply->updated_at) > 5) {
                    return redirect()->back()->withErrors('Your replied is older then 5 days cannot edit it any more!');
                }

                $reply->reply     = $request->reply_text;
                $reply->save();

                return redirect()->back()->with('edit', false)->with('review_id', $review->id);
            }
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply)
    {
        //
    }
}
