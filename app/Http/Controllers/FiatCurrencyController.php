<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFiatCurrencyRequest;
use App\Http\Requests\UpdateFiatCurrencyRequest;
use App\Models\FiatCurrency;

class FiatCurrencyController extends Controller
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
    public function store(StoreFiatCurrencyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FiatCurrency $fiatCurrency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FiatCurrency $fiatCurrency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiatCurrencyRequest $request, FiatCurrency $fiatCurrency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FiatCurrency $fiatCurrency)
    {
        //
    }
}
