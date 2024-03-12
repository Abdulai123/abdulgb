<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Http\Requests\StoreBugRequest;
use App\Http\Requests\UpdateBugRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BugController extends Controller
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
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }
        $user = auth()->user();
        return view('User.bugs', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBugRequest $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->role == 'store') {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/store/pgp/verify');
            }
        } else {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/pgp/verify');
            }
        }
        // Check the number 
        $bugsAddedToday = auth()->user()->bugs()->whereDate('created_at', Carbon::today())->count();
        // Limit the number of products a store can add in a day to 5
        $maxbugsPerDay = 5;

        if ($bugsAddedToday >= $maxbugsPerDay) {
            return redirect()->back()->withErrors('You have reached the maximum limit of bugs report per day, open a support ticket.');
        }

        $request->validate([
            'type'      => 'required|string|max:80',
            'content'  => 'required|min:500|max:5000',
            'captcha'   => 'required|string|min:8|max:8',
        ]);

        // 
        if ($request->captcha != session('captcha')) {
            return redirect()->back()->withErrors('Wrong captcha code, try again.');
        }

        $newBug = new Bug();
        $newBug->user_id  = auth()->user()->id;
        $newBug->type = $request->type;
        $newBug->content = $request->content;
        $newBug->save();
        if ($newBug->save()) {
            return redirect()->back()->with('success', 'Your report have been successfully sent, please wait for admin or mods to review your report, They will message you if your report is a valid bug. Thank you.');
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bug $bug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bug $bug)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBugRequest $request, Bug $bug)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bug $bug)
    {
        //
    }
}
