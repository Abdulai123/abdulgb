<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMirrorRequest;
use App\Http\Requests\UpdateMirrorRequest;
use App\Models\Mirror;

class MirrorController extends Controller
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
    public function store(StoreMirrorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Mirror $mirror)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mirror $mirror)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMirrorRequest $request, Mirror $mirror)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mirror $mirror)
    {
        //
    }
}
