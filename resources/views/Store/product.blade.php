<div>
    <div class="pname">
        <p>{{ $product->product_name }}</p>
    </div>
    <div class="image">
        @if (!empty($product->image_path1))
            <img src="data:image/png;base64,{{ $product_image[$product->image_path1] }}">
        @else
            <img src="data:image/png;base64,{{ $icon['default'] }}">
        @endif
    </div>
    <div class="cat-sub">
        <p class="{{ $product->payment_type }}">{{ '{' . $product->payment_type . '}' }}</p>
        <p class="parent-catg">{{ $product->parentCategory->name }}</p>
        <p class="sub-catg">{{ $product->subCategory->name }}</p>
    </div>
    <div class="price" style="margin: 10px">
        <h3>Price: ${{ $product->price }}</h3>
    </div>
    @if ($product->product_type === 'digital')
        @if ($product->auto_delivery_content != null or $product->auto_delivery_content != '')
            <h3 style="background-color: darkgoldenrod; color:#f1f1f1; border-radius: .3rem; margin-top: 0;">
                AutoShop: Yes</h3>
        @endif
    @endif
    <div class="buttons">
        <div>
            <form action="/store/{{ $store->store_name }}/do/product" method="post" style="margin-bottom: 0px;">
                <input type="hidden" name="product_id" id="" value="{{ Crypt::encrypt($product->id) }}">
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
                <a href="/store/{{ $store->store_name }}/show/view/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                    style="text-decoration: none; background-color: green; color: #f1f1f1; font-size: 1em; padding: 2px; border-radius: 3px; border: none; cursor: pointer;">View</a>
            </form>
        </div>
    </div>
    <hr style="width: 100%; border-radius: 100%; !important">
    <div class="desc">
        @php
            $avatarKey = $store->avatar;
        @endphp
        <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
            class="background-img"> |
        <span class="span1" title="In Stocks" style="display: flex; align-items: center;"><img
                src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter" width="15"> <span
                style="margin-left: .2em"> {{ $product->quantity }} |</span></span>
        <span class="span1" title="Sold" style="display: flex; align-items: center;"><img
                src="data:image/png;base64,{{ $icon['shopping-cart'] }}" class="icon-filter" width="15"> <span
                style="margin-left: .2em"> {{ $product->sold }} |</span></span>
        <span class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
            @php
                $weightedAverage = \App\Models\Review::claculateStoreRating($product->store_id);
            @endphp
            {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐</span>
    </div>
    @if ($product->status == 'Active')
        <p style="color: darkgreen;">{{ $product->status }}</p>
    @elseif ($product->status == 'Paused')
        <p style="color: darkred;">{{ $product->status }}</p>
    @elseif ($product->status == 'Pending')
        <p style="color: green;">{{ $product->status }}</p>
    @elseif ($product->status == 'Rejected')
        <p style="color: red;">{{ $product->status }}</p>
    @endif

</div>
