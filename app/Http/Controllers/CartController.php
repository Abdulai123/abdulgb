<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Category;
use App\Models\Escrow;
use App\Models\ExtraOption;
use App\Models\Promocode;
use App\Models\UserPromos;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
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
    public function create($name, $created_at)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $user = auth()->user();
        return view('User.cart', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        //foreach

        //return dd($userCarts);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $cart->quantity = $request->quantity;
        $cart->note    = $request->note;

        if ($cart->product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'There are minimum product than you request please chack your items or message the store about product out of stocks. ^.^');
        }

        if ($cart->save()) {
            return redirect()->back()->with('success', 'Successfully updated your cart.');
        }

        GeneralController::logUnauthorized($request, '404', '404 page returned');
        return redirect()->back();
    }

    public function checkAction(UpdateCartRequest $req, $user, $created_at, Cart $cart)
    {
        if ($created_at != strtotime($cart->created_at)) {
            return abort(404);
        }

        if (auth()->user()->id === $cart->user_id) {

            if ($req->update === '✔️') {
                return $this->update($req, $cart);
            } elseif ($req->remove === '❌') {
                return $this->destroy($cart);
            }

            return redirect()->back();
        }
        GeneralController::logUnauthorized($req, '404', '404 page returned');

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        // Delete the Cart instance
        $cart->id;
        $cart->delete();
        return redirect()->back()->with('delete', 'Cart successfully deleted.');
    }


    public function checkPromoInCart(Request $request)
    {
        // Check if the user is authenticated
        $user = auth()->user();

        if (!$user) {
            return abort(404); // or handle the unauthenticated user appropriately
            GeneralController::logUnauthorized($request, '404', '404 page returned, user modify his/her id in promo');
        }

        // Check if 'promocode' is set in the request
        $userEnteredPromo = $request->promocode;

        if (empty($userEnteredPromo)) {
            return redirect()->back()->with('emptycart', 'Promo code is required.');
        }

        // Check if the action is 'Apply Now'
        if ($request->action !== 'Apply Now') {
            return abort(404);
            GeneralController::logUnauthorized($request, '404', '404 page returned modifying form');
        }

        // Check if the user's cart is empty
        if ($user->carts->isEmpty()) {
            return redirect()->back()->with('emptycart', 'Insert products into your cart to apply promo code!!');
        }

        $errorMessages = '';
        $validPromoApplied = false;
        $matchingProductFound = false;

        // Find the promo code
        $matchingPromo = Promocode::where('code', $userEnteredPromo)->first();

        // Check if the promo code is valid
        if ($matchingPromo) {
            // Check each cart item for a matching product
            foreach ($user->carts as $cart) {
                if ($cart->product_id === $matchingPromo->product_id) {
                    $matchingProductFound = true;

                    // Check promo code conditions
                    if (now() > $matchingPromo->expiration_date || $matchingPromo->status == 'expired') {
                        $errorMessages = 'The promo code you entered has expired.';
                    } elseif ($matchingPromo->usage_limit == $matchingPromo->times_used) {
                        $errorMessages = 'The promo code you entered has reached its usage limits!';
                    } elseif (!$user->usedPromocodes->where('promocode_id', $matchingPromo->id)->isEmpty()) {
                        $errorMessages = 'You have already used this promo code.';
                    } else {
                        //Record that the user has used this promo code
                        $newUsePromo = new UserPromos();
                        $newUsePromo->user_id = $user->id;
                        $newUsePromo->promocode_id = $matchingPromo->id;
                        $newUsePromo->cart_id = $cart->id;
                        $newUsePromo->discount = $matchingPromo->discount;
                        $newUsePromo->save();

                        $matchingPromo->times_used += 1;
                        $matchingPromo->save();

                        $productCost = $matchingPromo->product->price * $cart->quantity;
                        $discountAmount = $matchingPromo->type === 'fixed' ? $matchingPromo->discount : ($productCost / 100) * $matchingPromo->discount;

                        $cart->discount = $discountAmount;
                        $cart->save();

                        $validPromoApplied = true;
                        break; // Break out of the loop after successfully applying the promo code
                    }
                }
            }
        }

        // Set the session variable with the promo-related messages
        if (!$matchingProductFound) {
            return redirect()->back()->with('invalidPromo', 'No matching product with the specified promo code in your cart.');
        } elseif (!empty($errorMessages)) {
            return redirect()->back()->with('promoErrors', $errorMessages);
        } elseif ($validPromoApplied) {
            return redirect()->back()->with('promoSuccess', 'You have successfully applied a promo code.');
        } else {
            // Redirect back if none of the conditions are met
            GeneralController::logUnauthorized($request, '404', '404 page returned');

            return redirect()->back();
        }
    }

    public function createOrder($name, $created_at)
    {
        $user = auth()->user();
        $productTotal = 0;
        $extraTotal = 0;

        foreach ($user->carts as $cart) {
            $extra = ExtraOption::find($cart->extra_option_id);
            $productTotal += (($cart->product->price * $cart->quantity) - $cart->discount);
            $extraTotal += $extra->cost;
        }

        $totalAmount = $productTotal + $extraTotal;


        if ($user->wallet->balance >= $totalAmount) {
            // Check out each cart and make order for it.
            foreach ($user->carts as $cart) {
                $order = OrderController::initiateOrder($user, $cart);
                if ($cart->cartUsedPromo) {
                    $currenPromo = UserPromos::where('cart_id', $cart->id)->first();
                    if ($currenPromo) {
                        $currenPromo->cart_state = 'checked_out';
                        $currenPromo->save();
                    }
                }

                $extra = ExtraOption::find($cart->extra_option_id);
                $amount = (($extra->cost + ($cart->product->price * $cart->quantity)) - $cart->discount);

                // deduct the amount from the user balance and add it to escrow...
                $user->wallet->balance -= $amount;
                $user->wallet->save();

                // Save the product sales
                $order->product->sold += $order->quantity;
                $order->product->quantity -= $order->quantity;
                $order->product->save();

                // Save the user deatils
                $order->user->total_orders += $order->quantity;
                $order->user->save();

                //save the store sales
                $order->store->width_sales += $order->quantity;
                $order->store->save();

                // if ($cart->product->payment_type == 'FE' && $cart->product->store->is_fe_enable === 1) {
                //     $cart->product->store->user->wallet->balance += $amount;
                //     $cart->product->store->user->wallet->save();
                // } else {
                //     // add funds to escrow
                $this->makeEscrow($order->id, $amount);
                // }

                $cart->delete();
            }

            return redirect()->back()->with('success', 'Great news! Your order is confirmed. Keep an eye on your notifications and orders in setting for order details. Meanwhile, explore more products in Whales Market.');
        } else {
            return redirect()->back()->with('delete', 'Insufficient funds! Please add more funds to complete this checkout.');
        }

        return redirect()->back();
    }


    // add the order to escrow
    private function makeEscrow($order, $amount)
    {
        $new_eascrow = new Escrow();
        $new_eascrow->order_id = $order;
        $new_eascrow->fiat_amount   = $amount;
        $new_eascrow->save();
    }
}
