<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsStatusRequest;
use App\Http\Requests\UpdateNewsStatusRequest;
use App\Models\NewsStatus;

class NewsStatusController extends Controller
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
    public function store(StoreNewsStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsStatus $newsStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsStatus $newsStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsStatusRequest $request, NewsStatus $newsStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsStatus $newsStatus)
    {
        //
    }
}
