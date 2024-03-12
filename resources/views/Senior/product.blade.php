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
                    <p class="contents" style="text-align: left; white-space: pre-wrap;">{{ $product->product_description }}</p>
                </div>
                @if ($product->return_policy != null)
                    <div class="refund-policy">
                        <h3>Refund Policy</h3>
                        <p style="text-align: left; white-space: pre-wrap;">{{ $product->return_policy }}</p>
                    </div>
                @endif
            </div>
        </div>


        {{-- product disply --}}

        <div class="main-store-div" style="margin: 1px;">
            <h3>Listing Owner Informatain</h3>
            <div class="s-main-image">
                @php
                    $avatarKey = $product->store->avatar;
                @endphp
                <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                    class="background-img">
                <div>
                    <div class="div-p">
                        <p class="store-name">{{ $product->store->store_name }}<span
                                style="font-size: .5em;">Store</span></p>
                        <p class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
                            @php
                                $weightedAverage = \App\Models\Review::claculateStoreRating($product->store_id);
                            @endphp
                            {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐</p>
                    </div>
                    <div style="margin-top: 0; display:flex; justify-content:space-around">

                        <span>S I N C E</span>
                        <span>{{ $product->store->created_at->format('d F, Y') }}</span>
                    </div>
                    <div class="div-p">
                        <p>Status: <span class="{{ $product->store->status }}">{{ $product->store->status }}</span>
                        </p> |
                        <p>Sales: {{ $product->store->width_sales }}</p>
                    </div>
                    <div class="div-p">
                        <p>Disputes: [<span style="color: #28a745;">Won
                                ({{ $product->store->disputes_won }})</span>/<span style="color:#dc3545;">Lost
                                ({{ $product->store->disputes_lost }})</span>]</p>
                    </div>
                    <div class="div-p">
                        <p>Listings: {{ $product->store->products->count() }}</p> |
                        <p>Favorited: {{ $product->store->StoreFavorited->count() }}</p>
                    </div>
                </div>
            </div>

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
                        <p>Product Type: {{ $product->product_type }}</p>
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
                            {{ session('xmr') != null ? round(($product->price / session('xmr')), 3) : null }} XMR
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
                    <a href="/senior/staff/show/product/reviews/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                        class="input-listing">See Reviews({{ $product->reviews->count() }})</a>
                    <div class="buttons">
                        <div>
                            <form action="" method="post" style=" margin-bottom: 0px;">
                                @csrf
                                @if ($product->status != 'Active')
                                    <input type="submit" name="approve"
                                        style="background-color: darkgreen; color: #f1f1f1; font-size: 1.2rem; padding: 5px; border-radius: 3px; border: none; cursor: pointer;"
                                        value="Approve">
                                @endif
                                <input type="submit" name="reject"
                                    style="background-color: darkred; color: #f1f1f1; font-size: 1.2rem; padding: 5px; border-radius: 3px; border: none; cursor: pointer;"
                                    value="Reject">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
