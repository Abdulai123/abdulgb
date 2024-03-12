<?php

namespace App\Http\Controllers;

use App\Models\UserPromos;
use App\Http\Requests\StoreUserPromosRequest;
use App\Http\Requests\UpdateUserPromosRequest;

class UserPromosController extends Controller
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
    public function store(StoreUserPromosRequest $request)
    {
        return dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPromos $userPromos)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPromos $userPromos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPromosRequest $request, UserPromos $userPromos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPromos $userPromos)
    {
        //
    }
}
