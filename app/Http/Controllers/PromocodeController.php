<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use App\Http\Requests\StorePromocodeRequest;
use App\Http\Requests\UpdatePromocodeRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PromocodeController extends Controller
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
    public function create(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($request->has('new_coupon')) {
            return redirect()->back()->with('new_coupon', true);
        }

        if ($request->has('save')) {
            $request->validate([
                'product' => 'required|string|min:32',
                'type'   => 'required|in:fixed,percentage',
                'code'    => 'required|min:4|max:50',
                'discount' => 'required|numeric|min:1',
                'expired_date' => 'required|date',
                'usage_limit'  => 'required|numeric|nullable',
                'captcha'   => 'required|string|min:8|max:8',
            ]);

            // 'captcha'   => 'required|string|min:8|max:8',
            if ($request->captcha != session('captcha')) {
                return redirect()->back()->withErrors('Wrong captcha code, try again.');
            }

            $product = Product::find(Crypt::decrypt($request->product));

            if ($product->store->store_name != auth()->user()->store->store_name) {
        GeneralController::logUnauthorized($request, 'Not his product', 'modifying the product info, not his product...');
                return redirect()->back()->withErrors('It looks like this product is not yours.');
            }

            if ($request->type == 'fixed' && $request->discount > $product->price) {
                return redirect()->back()->withErrors('The discount is greate than the product price.');
            }

            $coupon = new Promocode();
            $coupon->product_id      = $product->id;
            $coupon->store_id        = auth()->user()->store->id;
            $coupon->code            = $request->code;
            $coupon->discount        = $request->discount;
            $coupon->type            = $request->type;
            $coupon->expiration_date = $request->expired_date;
            $coupon->usage_limit     = $request->usage_limit;
            $coupon->times_used      = 0;
            $coupon->save();

            return redirect()->back()->with('success', "Coupon code created successfully, you can now share it with your buyers.");
        }

        if ($request->has('delete')) {
            $request->validate([
                'promo_id' => 'required|string|min:32',
            ]);

            $coupon = Promocode::find(Crypt::decrypt($request->promo_id));
            $coupon->delete();

            return redirect()->back()->with('success', 'You have successfully deleted a coupon code.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromocodeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Promocode $promocode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promocode $promocode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromocodeRequest $request, Promocode $promocode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocode $promocode)
    {
        //
    }
}
