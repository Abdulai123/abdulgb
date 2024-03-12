<div>
    @if (session('success') != null)
        <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
            {{ session('success') }}</p>
    @endif
    @if (session('error') != null)
        <p style="text-align: center; background: darkred; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
            {{ session('error') }}</p>
    @endif
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545">
                {{ $error }}
            </p>
        @endforeach
    @endif
</div>


<div class="products-overview">
    <h2>{{ $product->product_name }}</h2>
    <hr>
    <div class="desc-photo">
        <div class="photo">
            <div class="main-pic">
                @php
                    $img1 = $product->image_path1;
                @endphp
                <img src="data:image/png;base64,{{ !empty($product_image[$img1]) ? $product_image[$img1]  : $icon['default'] }}">
            </div>
            <div class="sub-pics">
               
                <img src="data:image/png;base64,{{ !empty($product_image[$img1]) ? $product_image[$img1] : $icon['default'] }}">

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
                    <p class="contents">{{ $product->product_description }}</p>
                </div>
                @if ($product->return_policy != null)
                    <div class="refund-policy">
                        <h3>Refund Policy</h3>
                        <p>{{ $product->return_policy }}</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="main-store-div" style="margin: 1px;">
            <div class="info-overvew">

                <h3>Listing Informatain</h3>
                <div style="margin: 1px;">
                    <p class="cls3" style="text-decoration: underline">Sold <span>
                            {{ $product->sold }}</span> <span>
                            Since {{ $product->created_at->format('d F, Y') }}</span>
                    <div class="div-p">
                        <p>Product Type: <span style="color: #28a745">{{ $product->product_type }}</span>
                        </p>
                        <p>In Stocks: <span style="color: #28a745">{{ $product->quantity }}</span>
                        </p>
                    </div>
                    <div class="div-p">
                        <p>Ship From: <span style="color: #28a745">{{ $product->ship_from }}</span>
                        </p>
                        <p>Ships To: <span style="color: #28a745">{{ $product->ship_to }}</span>
                        </p>
                    </div>
                    <div class="div-p">
                        <p>Payment Type: <span
                                class="{{ $product->payment_type }}">{{ '{' . $product->payment_type . '}' }}</span>
                        </p>
                        <p>Disputes: [<span style="color: #28a745;">Won
                                ({{ $product->disputes_won }})</span>/<span style="color:#dc3545;">Lost
                                ({{ $product->disputes_lost }})</span>]</p>
                    </div>
                </div>
                <p></p>
                <div>
                    <div class="extra">
                        <p>Extra/Shipping Options</p>
                        <select name="extra_shipping_option" id="extra-shipping-option">
                            @if ($product->extraShipping->count() > 0)
                                @forelse ($product->extraShipping as $extraShipping)
                                    <option value="{{ $extraShipping->id }}">
                                        {{ $extraShipping->name }}
                                        -
                                        USD + {{ $extraShipping->cost }} / order</option>
                                @empty
                                    <option value="1"> Default - USD + 0.00 / order</option>
                                @endforelse
                            @else
                                <option value="1"> Default - USD + 0.00 / order</option>
                            @endif
                        </select>
                    </div>
                    <div class="price" style="margin-bottom: 10px">
                        <h3>Price: ${{ $product->price }}</h3>
                        <h3 style="margin: 10px"> <img src="data:image/png;base64,{{ $icon['xmr'] }}" width="25">
                            0.000 XMR
                        </h3>
                        @if ($product->product_type === 'digital')
                            @if ($product->auto_delivery_content != null or $product->auto_delivery_content != '')
                                <h3
                                    style="background-color: darkgoldenrod; color:#f1f1f1; border-radius: .3rem; margin-top: 0;">
                                    AutoShop: Yes</h3>
                            @else
                                <h3
                                    style="background-color: darkgoldenrod; color:#f1f1f1; border-radius: .3rem; margin-top: 0;">
                                    AutoShop: No</h3>
                            @endif
                        @endif
                    </div>
                    <div class="buttons">
                        <div>
                            <form
                                action="/store/{{ $store->store_name }}/do/view/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                                method="post" style="margin-bottom: 0px;">
                                <input type="hidden" name="product_id" id=""
                                    value="{{ Crypt::encrypt($product->id) }}">
                                @csrf
                                <a href="/store/{{ $store->store_name }}/show/product-edit/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                                    style="text-decoration: none; background-color: darkblue; color: #f1f1f1; font-size: 1em; padding: 2px; border-radius: 3px; border: none; cursor: pointer;">Edit</a>
                                |
                                @if ($product->status != 'Paused')
                                    <input type="submit" name="statusChange"
                                        style="background-color: darkred; color: #f1f1f1; font-size: 1em; padding: 2px; border-radius: 3px; border: none; cursor: pointer;"
                                        value="Pause">
                                @else
                                    <input type="submit" name="statusChange"
                                        style="background-color: red; color: #f1f1f1; font-size: 1em; padding: 2px; border-radius: 3px; border: none; cursor: pointer;"
                                        value="UnPause">
                                @endif |
                                <input type="submit" name="boost"
                                    style="background-color: darkorange; color: #f1f1f1; font-size: 1em; padding: 2px; border-radius: 3px; border: none; cursor: pointer;"
                                    value="Boost">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-div">
    <h3>Reviews</h3>
    <div class="products-overview">
        <div style="display: flex; gap: 2em;" class="reviews">
            <table>
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

            <table>
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
        @forelse ($product->reviews()->paginate(15) as $review)
            <div class="displayed-reviews">
                <div class="reviewer-info">
                    <img src="data:image/png;base64,{{ $icon['user'] }}" class="icon-filter" width="25">
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
                        <p style="margin-top: 5px;"> {{ $review->comment }} <br>
                            
                            <form
                            action="/store/{{ $store->store_name }}/show/reply/review/{{ $review->created_at->timestamp }}/{{ $review->id }}"
                            method="post" style="margin:0px; padding:0px;">
                            @csrf
                            @if (session('new_reply') && $review->id == session('review_id'))
                                <textarea name="reply_text" id="" cols="30" style="width: 100%; border: 2px solid #4682B4;" rows="10"
                                    placeholder="Write here your reply and click again the reply button below to save this reply..."></textarea>
                            @endif
                            <p style='color: #4682B4; text-align: right; margin:0px; margin-top:12px;'>
                                @if (!$review->reply)
                                    <input type="submit" name="new_reply" class="input-listing" id=""
                                        value="Reply">
                                @endif
                                Last Updated: {{ $review->updated_at->format('d/m/y') }}
                            </p>
                            </p>
                        </form>

                        </p>
                    </div>
                </div>
            </div>
            {{-- Display here store replies --}}
            @if ($review->reply && $review->reply->count() > 0)
            <div class="displayed-reviews" style="margin-left: 1em; border: 2px solid #4682B4;">
                <div class="reviewer-info">
                    <img src="data:image/png;base64,{{ $icon['reply'] }}" class="icon-filter" width="25">
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
                        <form
                            action="/store/{{ $store->store_name }}/show/reply/review/{{ $review->created_at->timestamp }}/{{ $review->id }}"
                            method="post" style="margin:0px; padding:0px;">

                            @csrf
                            @if (session('edit') && $review->id == session('review_id'))
                            <input type="hidden" name="reply_id" value="{{ Crypt::encrypt($review->reply->id) }}">
                                <textarea name="reply_text" id="" cols="30" style="width: 100%; border: 2px solid #4682B4;" rows="5"
                                    placeholder="Write here your reply and click again the reply button below to save this reply...">{{ $review->reply->reply }}</textarea>
                                    <input type="hidden" name="save">
                            @else
                                <p style="margin-top: 5px;"> {{ $review->reply->reply }}</p>
                            @endif
                            <p style='color: #4682B4; text-align: right; margin:0px; margin-top:12px;'>
                                <input type="submit" name="edit" class="input-listing" id=""
                                    value="Edit & Save">
                                Last Updated: {{ $review->reply->updated_at->format('d/m/y') }}
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <p>No review found for this product, buy this product to leave a review!</p>
        @endforelse
        @if ($product->reviews->count() > 10)
            <a href=""
                style="text-decoration: underline">See All Reviews</a>
        @endif
    </div>
</div>
