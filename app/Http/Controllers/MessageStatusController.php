<?php

namespace App\Http\Controllers;

use App\Models\MessageStatus;
use App\Http\Requests\StoreMessageStatusRequest;
use App\Http\Requests\UpdateMessageStatusRequest;

class MessageStatusController extends Controller
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
    public function store(StoreMessageStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageStatus $messageStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageStatus $messageStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageStatusRequest $request, MessageStatus $messageStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageStatus $messageStatus)
    {
        //
    }
}
