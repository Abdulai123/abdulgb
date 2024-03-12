<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $user->public_name }} > Carts</title>
    <link rel="stylesheet" href="{{ asset('market.css') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @include('User.navebar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <p>
                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color:red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </p>
                <p style="color: green; text-align: center;">{{ session('success') }}</p>
                <p style="color: red; text-align: center;">{{ session('delete') }}</p>

                <div class="cart-div">
                    <div style="background-color: var(--white-background-color); border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom:1em;">
                        <h1 class="notifications-h1">Shopping Cart</h1>
                        <p class="notifications-p">For any change made to the cart please try to update it, else changes
                            will not
                            take
                            effect! <br>
                            Please encrypt all your address or any note for your own security <span
                                style="background-color: darkorange; color: var(--text-color-for-blue-bg);">highly
                                recommended</span>, <span style="color:red;">We do not encrypt notes which is in
                                cart!!!</span>
                        </p>

                        <table>
                            <thead style="font-family: Helvetica, sans-serif;">
                                <tr>
                                    <th>--{ }--</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Extra/Shipping</th>
                                    <th>Price/Item</th>
                                    <th>Discount</th>
                                    <th>Note/Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-family: Helvetica, sans-serif;">
                                @forelse ($user->carts as $cart)
                                    <form
                                        action="/cart/{{ $user->public_name }}/{{ $cart->created_at->timestamp }}/{{ $cart->id }}"
                                        method="post">
                                        @csrf
                                        @method('patch')
                                        <tr class="self-container">
                                            <td>
                                                @php
                                                    $img1 = $cart->product->image_path1;
                                                @endphp
                                                <img src="data:image/png;base64,{{ !empty($product_image[$img1]) ? $product_image[$img1] : $icon['default'] }}"
                                                    width="50">
                                            </td>
                                            <td><a
                                                    href="/listing/{{ $cart->product->created_at->timestamp }}/{{ $cart->product->id }}">{{ Str::limit($cart->product->product_name, 3, '...') }}</a>
                                            </td>
                                            <td><input type="number" name="quantity" id="" min="1"
                                                    value="{{ $cart->quantity }}"></td>
                                            <td>+${{ $cart->extraShipping->cost ?? '0.00' }}</td>
                                            <td>${{ $cart->product->price }}</td>
                                            <td>-${{ $cart->discount }}
                                            </td>
                                            <td>
                                                <textarea name="note" id="" style="width:100%;"
                                                    placeholder="Write here you note like: address or any note to send to this product owner." rows="2">{{ $cart->note != null ? $cart->note : '' }}</textarea>
                                            </td>
                                            <td>
                                                <input type="submit" name="remove" value="❌"
                                                    style="cursor: pointer; padding: 3px; border: none; background-color: darkred; color:#f1f1f1; font-size: 1rem; border-radius: .3rem; margin-bottom:10px;">
                                                <input type="submit" name="update" value="✔️"
                                                    style="cursor: pointer; padding: 3px; border: none; background-color: darkgreen; color:#f1f1f1; font-size: 1rem; border-radius: .3rem;">
                                            </td>
                                        </tr>
                                    </form>
                                @empty

                                    <tr>
                                        <td colspan="8">
                                            <span class="no-notification">
                                                Your cart is currently empty. Explore our products and add items to
                                                enjoy a
                                                delightful shopping experience.
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

            
                    <div style="background-color: var(--white-background-color); border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                        <h1 class="notifications-h1">Cart Summary</h1>
                        <table style=" font-family: Helvetica, sans-serif;">
                            <tbody>
                                <tr style="font-size: 0.8rem;">
                                    <td><strong>Sub Total: </strong></td>
                                    @php
                                        $total = 0;
                                    @endphp

                                    @foreach ($user->carts as $cart)
                                        @if ($cart->product)
                                            @php
                                                $total += $cart->product->price * $cart->quantity;
                                            @endphp
                                        @endif
                                    @endforeach

                                    <td>${{ round($total, 2) }}</td>
                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td><strong>Extra/Shipping Fees: </strong></td>
                                    <td>${{ $user->carts->sum('extraShipping.cost') }}</td>
                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td><strong>Discounts: </strong></td>
                                    <td>${{ $user->carts->sum('discount') }}</td>

                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td><strong>Total Cost USD: </strong></td>
                                    <td>${{ $user->carts->sum('extraShipping.cost') + ($total - $user->carts->sum('discount')) }}
                                    </td>
                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td><strong>Total Cost Monro: </strong></td>
                                    <td>{{ session('xmr') == null ? NUll : round( (($user->carts->sum('extraShipping.cost') + ($total - $user->carts->sum('discount'))) / session('xmr')) , 3)}} XMR</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <form action="/apply/promocode" method="post">
                            @if (session('promoSuccess'))
                                <p style="color:green">
                                    {{ session('promoSuccess') }}
                                    <br>

                                </p>
                            @endif

                            @if (session('promoErrors'))
                                <p style="color:red">
                                    {{ session('promoErrors') }}<br>
                                </p>
                            @endif

                            @if (session('invalidPromo'))
                                <p style="color:red">
                                    {{ session('invalidPromo') }}<br>
                                </p>
                            @endif

                            @if (session('emptycart'))
                                <p style="color:red">
                                    {{ session('emptycart') }}<br>
                                </p>
                            @endif

                            @csrf

                            <div class="coupon-code" style="margin: 2em;">
                                <input type="text" name="promocode" placeholder="Enter code: E.g., WHALESDAY"
                                    pattern="[A-Za-z0-9]+" class="apply_now_copons_text" style="margin-bottom: 1em;">
                                <input type="submit" name="action" class="apply_now_copons_btn" value="Apply Now">
                                <br>
                                <span style="font-size: 0.9rem; color: #4CAF50;">Important: Please ensure your cart is
                                    up to
                                    date before applying promo codes. Promo codes discount will be based on the current
                                    cart
                                    information.</span>

                            </div>
                        </form>
                        <form action="" method="post">
                            @csrf
                            <div class="proceed-now">
                                <img src="data:image/png;base64,{{ $icon['xmr'] }}" width="20">
                                <input type="submit" name="checkout_cart" value="CheckOut Cart">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('User.footer')
</body>

</html>
