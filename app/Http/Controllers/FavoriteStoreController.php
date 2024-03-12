<?php

namespace App\Http\Controllers;

use App\Models\FavoriteStore;
use App\Http\Requests\StoreFavoriteStoreRequest;
use App\Http\Requests\UpdateFavoriteStoreRequest;

class FavoriteStoreController extends Controller
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
    public function store(StoreFavoriteStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FavoriteStore $favoriteStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavoriteStore $favoriteStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteStoreRequest $request, FavoriteStore $favoriteStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavoriteStore $favoriteStore)
    {
        if ($favoriteStore->user_id === auth()->user()->id) {
            $favoriteStore->delete();
            return redirect()->back();
        }
        return abort(403);
    }
}
