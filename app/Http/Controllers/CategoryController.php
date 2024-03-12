<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Product;

class CategoryController extends Controller
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
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function parentCategoryProducts($created_at, Category $category)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        if ($created_at == strtotime($category->created_at)) {

            $query = Product::where('parent_category_id', $category->id)->where('status', 'Active');
            // Assuming the User model has a relationship named 'blockedStores'
            $userBlockedStoreIds = auth()->user()->blockedStores()->pluck('store_id')->toArray();

            $products = $query->whereHas('store', function ($query) use ($userBlockedStoreIds) {
                $query->where('status', 'Active')
                    ->whereNotIn('id', $userBlockedStoreIds); // Assuming 'id' is the primary key of the stores table
            })->paginate(20);

            return view('User.category', [
                'user'  => auth()->user(),
                'icon' => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'action' => 'users',
                'name' => 'Users',
                'parentCategories' => Category::whereNull('parent_category_id')->get(),
                'subCategories' => Category::where('parent_category_id', $category->id)->get(),
                'categories' => Category::all(),
                'is_parent_category' => true,
                'is_sub_category' => false,
                'categoryName' => $category->name,
            ]);
        }

        
        return abort(404);
    }



    public function subCategoryProducts($created_at, Category $category)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        if ($created_at == strtotime($category->created_at)) {

            $query = Product::where('sub_category_id', $category->id)->where('status', 'Active');
            // Assuming the User model has a relationship named 'blockedStores'
            $userBlockedStoreIds = auth()->user()->blockedStores()->pluck('store_id')->toArray();

            $products = $query->whereHas('store', function ($query) use ($userBlockedStoreIds) {
                $query->where('status', 'Active')
                    ->whereNotIn('id', $userBlockedStoreIds); // Assuming 'id' is the primary key of the stores table
            })->paginate(20);

            return view('User.subcategory', [
                'products' => $products,
                'user'  => auth()->user(),
                'icon' => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'action' => 'users',
                'name' => 'Users',
                'parentCategories' => Category::whereNull('parent_category_id')->get(),
                'subCategories' => Category::whereNotNull('parent_category_id')->get(),
                'categories' => Category::all(),
                'is_parent_category' => false,
                'is_sub_category' => true,
                'categoryName' => $category->name,
            ]);
        }
            return abort(404);

    }
}
