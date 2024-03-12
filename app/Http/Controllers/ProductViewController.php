<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductViewRequest;
use App\Http\Requests\UpdateProductViewRequest;
use App\Models\ProductView;

class ProductViewController extends Controller
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
    public function store(StoreProductViewRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductView $productView)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductView $productView)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductViewRequest $request, ProductView $productView)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductView $productView)
    {
        //
    }
}
