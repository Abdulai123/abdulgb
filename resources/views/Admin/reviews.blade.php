<h3 style="text-transform: capitalize; text-align: center;">
    <span style="color:#28a745">
        All Reviews ({{ $reviews->count() }})
    </span>
    <hr>
</h3>


<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Store Name</th>
            <th>Type</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/reviews/search" method="get"
            style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ old('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </td>
                <td>
                    <select name="number_of_rows" id="number_of_rows">
                        <option value="50" {{ old('number_of_rows') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ old('number_of_rows') == '100' ? 'selected' : '' }}>100</option>
                        <option value="150" {{ old('number_of_rows') == '150' ? 'selected' : '' }}>150</option>
                        <option value="250" {{ old('number_of_rows') == '250' ? 'selected' : '' }}>250</option>
                    </select>
                </td>
                <td>
                    <select name="store" id="">
                        <option value="" {{ old('store_name') == '' ? 'selected' : '' }}>
                            All({{ \App\Models\Store::all()->count() }})</option>
                        @foreach (\App\Models\Store::all() as $store)
                            <option value="{{ $store->id }}"
                                {{ old('store_name') == $store->store_name ? 'selected' : '' }}>
                                {{ $store->store_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="type" id="number_of_rows">
                        <option value="all" {{ old('type') == 'all' ? 'all' : '' }}>All</option>
                        <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>Positive</option>
                        <option value="neutral" {{ old('type') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                        <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>Negative</option>
                    </select>
                </td>
                <td style="text-align: center; margin:0px; padding:0px;">
                    <input type="submit" class="submit-nxt" style="width: max-content; margin:0px; padding:.5em;"
                        value="Perform">
                </td>
            </tr>
        </form>
    </tbody>
</table>

@php
    $reviews = session('reviews') ?? $reviews;
@endphp

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
                    <td>{{ $reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'positive')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 1.3rem; text-align:center;">⏹️</td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Neutral</td>
                    <td>{{ $reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'neutral')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 1.3rem; text-align:center;">⛔</td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Negative</td>
                    <td>{{ $reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonth(), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subMonths(6), now()])->count() }}
                    </td>
                    <td>{{ $reviews->where('feedback', 'negative')->whereBetween('created_at', [now()->subYear(), now()])->count() }}
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
                        $averageRating = $reviews->avg($columnName);

                        // Check if the average rating is not null before rounding
                        $roundedRating = !is_null($averageRating) ? round($averageRating, 2) : null;
                    @endphp

                    <tr>
                        <td style="font-weight: bold; font-size:1.2rem; color:inherit;">
                            {{ $categoryName }}</td>
                        <td>{{ $roundedRating ?? 'N/A' }} ⭐</td>
                        <td>({{ $reviews->count() }})</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
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
                        href="/whales/admin/show/product/{{ $review->product->created_at->timestamp }}/{{ $review->product->id }}">{{ $review->product->product_name }}</a>

                    @if (
                        $review->comment ===
                            'This review has been flagged as spam for content review. Our team is on it to maintain a quality experience. Thanks for your vigilance in keeping our platform authentic!')
                        <p style="margin-top: 5px; color:red;"><i>{{ $review->comment }}</i> <span style="color:darkorange;">/M/Auto Mod/</span></p>
                    @else
                        <p style="margin-top: 5px;"> {{ $review->comment }} <br>

                        <form action="/whales/admin/{{ $user->public_name }}/flag/review/{{ $review->id }}"
                            method="post" style="margin:0px; padding:0px;">
                            @csrf

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

                        <form action=""
                            method="get" style="margin:0px; padding:0px;">

                            @csrf

                            <p style="margin-top: 5px;"> {{ $review->reply->reply }}</p>

                            <p style='color: #4682B4; text-align: right; margin:0px; margin-top:12px;'>
                                
                                Last Updated: {{ $review->reply->updated_at->format('d/m/y') }}
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        @endif
    @empty
        <p>No review found, come back later and check!</p>
    @endforelse


    {{-- Custom Pagination Links --}}
    {{ $reviews->render('vendor.pagination.custom_pagination') }}

</div>
