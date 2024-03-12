<h3 style="text-transform: capitalize; text-align: center;">
    <span style="color:#28a745">
        {{ $show_user->public_name }} > Reviews Left For Stores
    </span>
    <hr>
</h3>
@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('success') }}</p>
@endif
<div>
    @if ($errors->any())
        <ul style="margin: auto; list-style-type: none; padding: 0; text-align: center;">
            @foreach ($errors->all() as $error)
                <li style="color: red;">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</div>
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
                    <td>{{ $show_user->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 1.3rem; text-align:center;">⏹️</td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Neutral</td>
                    <td>{{ $show_user->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 1.3rem; text-align:center;">⛔</td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Negative</td>
                    <td>{{ $show_user->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $show_user->reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
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
                        $averageRating = $show_user->reviews->avg($columnName);

                        // Check if the average rating is not null before rounding
                        $roundedRating = !is_null($averageRating) ? round($averageRating, 2) : null;
                    @endphp

                    <tr>
                        <td style="font-weight: bold; font-size:1.2rem; color:inherit;">
                            {{ $categoryName }}</td>
                        <td>{{ $roundedRating ?? 'N/A' }} ⭐</td>
                        <td>({{ $show_user->reviews->count() }})</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    @php
        $reviews = $show_user->reviews;
    @endphp
    @forelse ($reviews as $review)
        <div class="displayed-reviews" style="padding: 0px;">
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
                    <a
                        href="/senior/staff/show/product/{{ $review->product->created_at->timestamp }}/{{ $review->product->id }}">{{ $review->product->product_name }}</a>
                        @if (
                            $review->comment ===
                                'This review has been flagged as spam for content review. Our team is on it to maintain a quality experience. Thanks for your vigilance in keeping our platform authentic!')
                            <p style="margin-top: 5px; color:red;"><i>{{ $review->comment }}</i> <span style="color:darkorange;">/M/Auto Mod/</span></p>
                        @else
                            <p style="margin-top: 5px;"> {{ $review->comment }} <br>
    
                            <form action=""
                                method="post" style="margin:0px; padding:0px;">
                                @csrf
    
                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                <p style='color: #4682B4; text-align: right; margin:0px; margin-top:12px;'>
    
                                    <input type="submit" name="flag_reply" class="input-listing" value="Flag As Spam">
    
                                    Price: ${{ $review->product->price }}
                                    Last Updated: {{ $review->updated_at->format('d/m/y') }}
                                </p>
    
                            </form>
                            </p>
                        @endif
                </div>
            </div>
        </div>
        {{-- Display here store replies --}}
        @if ($review->reply && $review->reply->count() > 0)
            <div class="displayed-reviews" style="margin-left: 1em; border: 2px solid #4682B4; padding:0px;">
                <div class="reviewer-info">
                    <img src="data:image/png;base64,{{ $icon['reply'] }}" class="icon-filter" width="25">
                    <p><span>{{ $review->product->store->store_name }}</span>
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

                        <form action="/senior/staff/{{ $user->public_name }}/flag/review/{{ $review->id }}"
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
        <p>No review found for this user, come back later and check!</p>
    @endforelse

</div>
