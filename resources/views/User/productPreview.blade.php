<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | Listing > {{ $product->product_name }}</title>
    <link rel="stylesheet" href="{{ asset('market.css') }}">

    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

</head>

<body>
    @include('User.navebar')

    <div class="container">
        <div class="main-div">
            <div>
                @if (session('error'))
                    <p
                        style="text-align:center; padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545; color: #f1f1f1;">
                        {{ session('error') }}
                    </p>
                @elseif (session('success'))
                    <p
                        style="text-align:center; padding: 10px; margin: 10px; border-radius: .5rem; background-color:#28a745; color:#f1f1f1;">
                        {{ session('success') }}
                    </p>
                @endif
                @if ($errors->any)
                    @foreach ($errors->all() as $error)
                        <p
                            style="text-align:center; padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545; color:#f1f1f1;">
                            {{ $error }}
                        </p>
                    @endforeach
                @endif
            </div>

            {{-- main part of the product display --}}
            <div class="products-overview">
                <form action="" method="post">
                    @csrf
                    <h2>{{ $product->product_name }}</h2>
                    <hr>
                    <div class="desc-photo">
                        <div class="photo">
                            <div class="main-pic">
                                @php
                                    $img1 = $product->image_path1;
                                @endphp
                                <img
                                    src="data:image/png;base64,{{ !empty($product_image[$img1]) ? $product_image[$img1] : $icon['default'] }}">
                            </div>
                            <div class="sub-pics">

                                <img
                                    src="data:image/png;base64,{{ !empty($product_image[$img1]) ? $product_image[$img1] : $icon['default'] }}">

                                @if ($product->image_path2 != null)
                                    <img src="data:image/png;base64,{{ $product_image[$product->image_path2] }}">
                                @endif

                                @if ($product->image_path3 != null)
                                    <img src="data:image/png;base64,{{ $product_image[$product->image_path3] }}">
                                @endif

                            </div>
                            <div class="desc-others">
                                <div class="description">
                                    <h3>DESCRIPTIONS</h3>
                                    <p class="contents" style="text-align: left; white-space: pre-wrap;">
                                        {{ $product->product_description }}</p>
                                </div>
                                @if ($product->return_policy != null)
                                    <div class="refund-policy">
                                        <h3>Refund Policy</h3>
                                        <p style="text-align: left; white-space: pre-wrap;">
                                            {{ $product->return_policy }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="main-store-div" style="margin: 1px;">
                            <div class="info-overvew">
                                {{-- (... second from for the user to complete the order) --}}
                                @if (session('enter_adderss'))
                                    <h3 style="margin-bottom: 0px;">Store Public PGP Key</h3>
                                    <p style="margin:0px;font-size:.7rem;">use this store public PGP KEY below to
                                        encrypt your sensitive data!</p>

                                    <textarea name="store_pgp" id="" cols="30" rows="10" width="100" style="width: 100%;">{{ $product->store->user->pgp_key }}</textarea>


                                    <h4 style="margin-bottom: 0px;">Please Enter below your address/notes (Optionals...)
                                    </h4>
                                    <p style="margin:0px;font-size:.7rem;">Encrypt all address or any sensitive data
                                        yourself, use the store key above!</p>
                                    <textarea name="address_text"
                                        placeholder="Write or paste your note here (if you have any) like: address or any note to send to this product owner."
                                        cols="30" rows="10" width="100" style="width: 100%;"></textarea>


                                    <div>
                                        <p
                                            style="font-family: Arial, sans-serif; font-size: 18px; line-height: 1.1; margin-bottom: 10px;">
                                            ⚠️ <span style="font-size:.8rem;">If your data is sensitive and you do not
                                                encrypt it please slecet the check button below
                                                so our system will encrypt everything for you. </span>
                                        </p>
                                        <label for="">
                                            Yes encrypt for me? <input type="checkbox" name="encrypt_for_me"
                                                id="">
                                        </label>
                                        <input type="hidden" name="extra_shipping_option"
                                            value="{{ session('extra_shipping_option') }}">
                                        <input type="hidden" name="items" value="{{ session('items') }}">
                                        <div style="display: flex;justify-content:center; margin-bottom: 15px;">

                                            <div class="buy-now-cart" title="complete order now">
                                                <input type="submit" name="complete_order" value="Done">
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    <h3>Store Informatain</h3>
                                    @foreach ($user->favoriteStores as $favoriteStore)
                                        @if ($favoriteStore->store_id === $product->store_id)
                                            <p style="color: green; margin: 0; font-weight: lighter;">This store is one
                                                of
                                                your favorite store!</p>
                                        @endif
                                    @endforeach
                                    <div class="main-store-div" style="margin: 1px;">
                                        <div class="s-main-image">
                                            @php
                                                $avatarKey = $product->store->avatar;
                                            @endphp
                                            <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                                                class="background-img">
                                            <div>
                                                <div class="div-p">
                                                    <p class="store-name">{{ $product->store->store_name }}<span
                                                            style="font-size: .5em;">Store
                                                            @if ($product->store->is_verified)
                                                                <img src="data:image/png;base64,{{ $icon['verify'] }}"
                                                                    title="Verified Seller" width="30" />
                                                            @endif
                                                        </span></p>
                                                    <p class="span3"
                                                        style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
                                                        @php
                                                            $weightedAverage = \App\Models\Review::claculateStoreRating(
                                                                $product->store_id,
                                                            );
                                                        @endphp
                                                        {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐</p>
                                                </div>
                                                <div style="margin-top: 0; display:flex; justify-content:space-around">

                                                    <span>S I N C E</span>
                                                    <span>{{ $product->store->created_at->format('d F, Y') }}</span>
                                                </div>
                                                <div class="div-p">
                                                    <p>Status: <span
                                                            class="{{ $product->store->status }}">{{ $product->store->status }}</span>
                                                    </p> |
                                                    <p>Sales: {{ $product->store->width_sales }}</p> |
                                                </div>
                                                <div class="div-p">
                                                    <p>Disputes: [<span style="color: #28a745;">Won
                                                            ({{ $product->store->disputes_won }})</span>/<span
                                                            style="color:#dc3545;">Lost
                                                            ({{ $product->store->disputes_lost }})</span>]</p>
                                                </div>
                                                <div class="div-p">
                                                    <p>Listings:
                                                        {{ $product->store->products()->where('status', 'Active')->count() }}
                                                    </p> |
                                                    <p>Favorited: {{ $product->store->StoreFavorited->count() }}</p>
                                                </div>
                                                <div class="div-p">
                                                    <p style="border-bottom: 3px dotted green">Last Seen:
                                                        {{ \Carbon\Carbon::parse($product->store->user->last_seen)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="div-p" style="display: flex; gap: 1.3em; justify-content:center;">
                                            <a href="/store/show/message/{{ $product->store->store_name }}/{{ $product->store->created_at->timestamp }}/{{ $product->store->id }}"
                                                class="input-listing"> Message</a>
                                            <a href="/store/show/{{ $product->store->store_name }}/{{ $product->store->created_at->timestamp }}/{{ $product->store->id }}"
                                                class="input-listing">See
                                                Store</a>
                                            @if ($user->favoriteStores->count() > 0)
                                                @php $storeFavorited = false; @endphp
                                                @foreach ($user->favoriteStores as $favoriteStore)
                                                    @if ($favoriteStore->store_id === $product->store_id)
                                                        <a href="/favorite/f_store" class="input-listing">
                                                            UnFavorite</a>
                                                        @php $storeFavorited = true; @endphp
                                                    @endif
                                                @endforeach

                                                @if (!$storeFavorited)
                                                    <input type="submit" name="favorite_store" class="input-listing"
                                                        value="Favorite Store">
                                                @endif
                                            @else
                                                <input type="submit" name="favorite_store" class="input-listing"
                                                    value="Favorite Store">
                                            @endif
                                            <input type="submit" name="block_store" value="Block Store"
                                                class="input-listing">
                                            <a href="/report/store/{{ $product->store->store_name }}/{{ $product->store->created_at->timestamp }}/{{ $product->store->id }}"
                                                class="input-listing"> Report </a>

                                        </div>
                                    </div>


                                    <h3>Listing Informatain</h3>
                                    @foreach ($user->favoriteListings as $favoriteListing)
                                        @if ($favoriteListing->product_id === $product->id)
                                            <p style="color: green; margin: 0; font-weight: lighter;">This listing has
                                                been
                                                favorited by you!</p>
                                        @endif
                                    @endforeach
                                    <div class="main-store-div" style="margin: 1px;">
                                        <p class="cls3" style="text-decoration: underline">Sold <span>
                                                ({{ $product->sold }})</span> <span>
                                                Since {{ $product->created_at->format('d F, Y') }}</span>
                                        <div class="div-p">
                                            <p>Product Type: <span
                                                    style="color: #28a745">{{ $product->product_type }}</span>
                                            </p>
                                            <p>In Stocks: <span style="color: #28a745">{{ $product->quantity }}</span>
                                            </p>
                                        </div>
                                        <div class="div-p">
                                            <p>Ship From: <span
                                                    style="color: #28a745">{{ $product->store->ship_from }}</span>
                                            </p>
                                            <p>Ships To: <span style="color: #28a745">{{ $product->ship_to }}</span>
                                            </p>
                                        </div>
                                        <div class="div-p">
                                            <p>Payment Type: <span
                                                    class="{{ $product->payment_type }}">{{ '{' . $product->payment_type . '}' }}</span>
                                            </p>
                                            <p>Disputes: [<span style="color: #28a745;">Won
                                                    ({{ $product->disputes_won }})</span>/<span
                                                    style="color:#dc3545;">Lost
                                                    ({{ $product->disputes_lost }})</span>]</p>
                                        </div>
                                        <div class="div-p"
                                            style="display: flex; gap: 1.3em; justify-content:center;">
                                            @if ($user->favoriteListings->count() > 0)
                                                @php $listingFavorited = false; @endphp
                                                @foreach ($user->favoriteListings as $favoriteListing)
                                                    @if ($favoriteListing->product_id === $product->id)
                                                        <a href="/favorite/f_listing" class="input-listing">
                                                            UnFavorite
                                                            Listing</a>
                                                        @php $listingFavorited = true; @endphp
                                                    @endif
                                                @endforeach
                                                @if (!$listingFavorited)
                                                    <input type="submit" name="favorite_listing"
                                                        class="input-listing" value="Favorite Listing">
                                                @endif
                                            @else
                                                <input type="submit" name="favorite_listing" class="input-listing"
                                                    value="Favorite Listing">
                                            @endif
                                            <a href="/report/listing/{{ $product->product_name }}/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                                                class="input-listing"> Report Listing</a>
                                        </div>
                                    </div>
                                @endif
                                <p></p>
                                @if (session('enter_adderss'))
                                @else
                                    <div>
                                        <div class="extra">
                                            <p>Extra/Shipping Options</p>
                                            <select name="extra_shipping_option" id="extra-shipping-option">
                                                @foreach ($product->extraShipping as $extraShipping)
                                                    <option value="{{ $extraShipping->id }}"
                                                        style="text-transform: capitalize">
                                                        {{ $extraShipping->name }}
                                                        -
                                                        USD + {{ $extraShipping->cost }} / order</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="price" style="margin-bottom: 10px">
                                            <h3>Price: ${{ $product->price }}</h3>
                                            <h3 style="margin: 10px"> <img
                                                    src="data:image/png;base64,{{ $icon['xmr'] }}" width="25">
                                                {{ session('xmr') != null ? round($product->price / session('xmr'), 3) : null }}
                                                XMR
                                            </h3>
                                            @if ($product->product_type === 'digital')
                                                @if ($product->auto_delivery_content != null or !empty($product->auto_delivery_content))
                                                    <h3
                                                        style="background-color: darkgoldenrod; color:#f1f1f1; border-radius: .3rem; margin-top: 0;">
                                                        AutoShop: Yes</h3>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="price" style="margin-bottom: 10px">
                                            <input type="number" name="items" min="1" pattern="[0-9]+"
                                                value="1" required>
                                        </div>
                                        <div
                                            style="display: flex; gap: 2em; justify-content:center; margin-bottom: 15px;">
                                            <div class="buy-now-cart" title="add this item in cart">
                                                <input type="submit" name="add_to_cart" value="Add to cart">
                                            </div>
                                            <div class="buy-now">
                                                <img style="width: 20px; height: 20px;" src="/public/site-img/xmr.png"
                                                    alt="" srcset="">
                                                <input type="submit" name="buy_now" value="BUY NOW">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>




            {{-- main part of the comment display --}}
            <div class="main-div">

                <div class="products-overview">
                    <div style="display: flex; gap: 2em;" class="reviews">
                        <table style="border: 1px solid gray;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>1 Month</th>
                                    <th>6 Months</th>
                                    <th>12 Months</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-size: 1.3rem; text-align:center;">➕</td>
                                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Positive</td>
                                    <td>{{ $product->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.3rem; text-align:center;">⏹️</td>
                                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Neutral</td>
                                    <td>{{ $product->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.3rem; text-align:center;">⛔</td>
                                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Negative</td>
                                    <td>{{ $product->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                                    </td>
                                    <td>{{ $product->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table style="border: 1px solid gray;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Rating</th>
                                    <th>Based on Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $categories = [
                                        'Price' => 'price_rating',
                                        'Product' => 'product_rating',
                                        'Shipping Speed' => 'shipping_speed_rating',
                                        'Communication' => 'communication_rating',
                                    ];
                                @endphp

                                @foreach ($categories as $categoryName => $columnName)
                                    @php
                                        // Calculate the average rating for the current category
                                        $averageRating = $product->reviews->avg($columnName);

                                        // Check if the average rating is not null before rounding
                                        $roundedRating = !is_null($averageRating) ? round($averageRating, 2) : null;
                                    @endphp

                                    <tr>
                                        <td style="font-weight: bold; font-size:1.2rem; color:inherit;">
                                            {{ $categoryName }}</td>
                                        <td>{{ $roundedRating ?? 'N/A' }} ⭐</td>
                                        <td>({{ $product->reviews->count() }})</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <h3 style="text-transform: capitalize; text-align: center;">
                        <span style="color:#28a745">
                            Listing's Reviews
                        </span>
                        <hr>
                    </h3>
                    @forelse ($product->reviews()->paginate(10) as $review)
                        <div class="displayed-reviews">
                            <div class="reviewer-info">
                                <img src="data:image/png;base64,{{ $icon['user'] }}" class="icon-filter"
                                    width="25">
                                <p><span>{{ substr($review->user->public_name, 0, 1) . str_repeat('*', max(strlen($review->user->public_name) - 2, 0)) . substr($review->user->public_name, -1) }}</span>
                                </p>
                            </div>
                            <div class="reviewer-reviews">
                                <div class="reviews-rating">
                                    <div>
                                        <span>{{ $review->created_at->format('d/m/y') }}</span>
                                    </div>
                                    <div class="three" style="margin-top: .4em">
                                        @if ($review->feedback === 'positive')
                                            <span class="pstv">Positive</span>
                                        @elseif ($review->feedback === 'neutral')
                                            <span class="ntrl">Neutral</span>
                                        @elseif ($review->feedback === 'negative')
                                            <span class="ngtv">Negative</span>
                                        @endif

                                    </div>
                                </div>
                                <div class="rating-texts">
                                    @if (
                                        $review->comment ===
                                            'This review has been flagged as spam for content review. Our team is on it to maintain a quality experience. Thanks for your vigilance in keeping our platform authentic!')
                                        <p style="margin-top: 5px; color:red;"><i>{{ $review->comment }}</i> <span
                                                style="color:darkorange;">/M/Auto Mod/</span></p>
                                    @else
                                        <p style="margin-top: 5px;"> {{ $review->comment }} <br>
                                        <p style='color: #4682B4; text-align: right;'> Last Updated:
                                            {{ $review->updated_at->format('d/m/y') }}</p>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Display here store replies --}}
                        @if ($review->reply && $review->reply->count() > 0)
                            <div class="displayed-reviews" style="margin-left: 1em; border: 2px solid #4682B4;">
                                <div class="reviewer-info">
                                    <img src="data:image/png;base64,{{ $icon['reply'] }}" class="icon-filter"
                                        width="25">
                                    <p><span>{{ $product->store->store_name }}</span>
                                    </p>
                                </div>
                                <div class="reviewer-reviews">
                                    <div class="reviews-rating">
                                        <div>
                                            <span>{{ $review->created_at->format('d/m/y') }}</span><br>
                                            <span>Store Reply</span>
                                        </div>
                                    </div>
                                    <div class="rating-texts">
                                        <p style="margin-top: 5px;"> {{ $review->reply->reply }} <br>
                                        <form action="" method="post" style="margin:0px; padding:0px;">
                                            <p style='color: #4682B4; text-align: right; margin:0px; margin-top:12px;'>

                                                Last Updated: {{ $review->reply->updated_at->format('d/m/y') }}
                                            </p>
                                        </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p>No review found for this product, buy this product to leave a review!</p>
                    @endforelse
                    @if ($product->reviews->count() > 10)
                        <a href="/listing/reviews/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                            style="text-decoration: underline">See All Reviews</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
