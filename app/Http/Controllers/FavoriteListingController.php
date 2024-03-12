<?php

namespace App\Http\Controllers;

use App\Models\FavoriteListing;
use App\Http\Requests\StoreFavoriteListingRequest;
use App\Http\Requests\UpdateFavoriteListingRequest;

class FavoriteListingController extends Controller
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
    public function store(StoreFavoriteListingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FavoriteListing $favoriteListing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavoriteListing $favoriteListing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteListingRequest $request, FavoriteListing $favoriteListing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavoriteListing $favoriteListing)
    {
        if ($favoriteListing->user_id === auth()->user()->id) {
            $favoriteListing->delete();
            return redirect()->back();
        }
        return abort(403);
    }
}
