<?php

namespace App\Http\Controllers;

use App\Models\BlockStore;
use App\Http\Requests\StoreBlockStoreRequest;
use App\Http\Requests\UpdateBlockStoreRequest;

class BlockStoreController extends Controller
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
    public function store(StoreBlockStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BlockStore $blockStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlockStore $blockStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlockStoreRequest $request, BlockStore $blockStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlockStore $blockStore)
    {
        if ($blockStore->user_id === auth()->user()->id) {
            $blockStore->delete();
            return redirect()->back();
        }
        return abort(403);
    }
}
