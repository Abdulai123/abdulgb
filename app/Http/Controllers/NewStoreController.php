<?php

namespace App\Http\Controllers;

use App\Models\NewStore;
use App\Http\Requests\StoreNewStoreRequest;
use App\Http\Requests\UpdateNewStoreRequest;
use App\Models\Category;
use App\Models\Waiver;
use Illuminate\Http\Request;

class NewStoreController extends Controller
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
        return view('User.open-store', [
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
    public function store(StoreNewStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NewStore $newStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewStore $newStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewStoreRequest $request, NewStore $newStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewStore $newStore)
    {
        //
    }

    public function waiver()
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $user = auth()->user();

        return view('User.waiver', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }

    public function waiverAdd(Request $request)
    {
        $user = auth()->user();

        $user = auth()->user();

        $data = $request->validate([
            'reason' => 'required|string|min:50|max:2000',
            'proof1' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'proof2' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'proof3' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $newStore = new Waiver();
        $newStore->user_id = $user->id;
        $newStore->reason = $data['reason'];
        // Process and store images
        $newStore->proof1 = GeneralController::processAndStoreImage($data['proof1'], 'Waiver_Images');
        $newStore->proof2 = GeneralController::processAndStoreImage($data['proof2'], 'Waiver_Images');
        $newStore->proof3 = GeneralController::processAndStoreImage($data['proof3'], 'Waiver_Images');

        // Save the new store
        $newStore->save();

        return redirect()->back()->with('success', 'Your store waiver haven been submitted successfully, please wait while the team review your application this may take from within a day to 7 days max.');
    }
}
