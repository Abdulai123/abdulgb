{{-- 
<div>

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
          
            <a href="/store/show/{{ $product->store->store_name }}/{{ $product->store->created_at->timestamp }}/{{ $product->store_id }}">Store({{ $product->store->products->count() }})</a> |
            <a href="/listing/{{ $product->created_at->timestamp }}/{{ $product->id }}" style="background-color: green;">Buy Now</a> 
        </div>
    </div>
    <hr style="width: 100%; border-radius: 100%; !important">
    <div class="desc">
        @php
        $avatarKey = $product->store->avatar;
    @endphp
<img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
class="background-img">
|
        <span class="span1" title="In Stocks" style="display: flex; align-items: center;"><img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter" width="15"> <span style="margin-left: .2em"> {{ $product->quantity }} |</span></span>
        <span class="span1" title="Sold" style="display: flex; align-items: center;"><img src="data:image/png;base64,{{ $icon['shopping-cart'] }}" class="icon-filter" width="15"> <span style="margin-left: .2em"> {{ $product->sold }} |</span></span>
        <span class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:3px;">
            @php
            $weightedAverage = \App\Models\Review::claculateStoreRating($product->store_id);
            @endphp
            {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐</span>
    </div>
</div> --}}


@php
$weightedAverage = \App\Models\Review::claculateStoreRating($product->store_id);
@endphp


<div class="relative p-2 flex-grow rounded-md shadow-md border border-gray-500 hover:border-8 m-2 hover:border-gray-700 hover:text-gray-700 active:border-gray-800 active:text-gray-800 transition-all duration-300">
    {{-- <div class="absolute top-20 left-0 transform -rotate-45 origin-top-left bg-orange-600 p-1 z-30 rounded-md">
        <span class="bg-orange-600 text-xs uppercase font-bold text-white-500 ">Featured</span>
    </div> --}}
    {{-- <div class="absolute top-20 left-0 transform -rotate-45 origin-top-left bg-green-700 p-1 z-50 rounded-md">
        <span class="bg-green-700 text-xs uppercase font-bold text-white-500 ">Promoted</span>
    </div> --}}
    <div class="mb-4 md:mb-0 group">
        <a href="/listing/{{ $product->created_at->timestamp }}/{{ $product->id }}"
            class="font-bold text-2xl hover:underline text-sky-700">{{ Str::limit($product->product_name, 80, '...') }}</a>
    </div>
    
    
    <div class="flex flex-col md:flex-row items-start group font-sans">
        <div class="w-full md:max-w-fit flex flex-col md:flex-row gap-2 items-center">
            <div class="w-32 h-32 overflow-hidden">
                @if ($product->image_path1 != null)
                    <img src="data:image/png;base64,{{ $product_image[$product->image_path1] }}"
                        class="w-full h-full object-cover transform transition-transform hover:scale-110 cursor-col-resize"
                        alt="Product Image">
                @else
                    <img src="data:image/png;base64,{{ $icon['default'] }}"
                        class="w-full h-full object-cover transform transition-transform hover:scale-110 cursor-col-resize"
                        alt="Default Image">
                @endif
            </div>
            <div class="flex flex-row flex-grow md:ml-2 gap-2 text-sm">
                <div class="flex flex-col gap-2 items-left">
                    <p>Sold: <span class="font-semibold">50</span></p>
                    <p>Likes: <span class="font-semibold">50</span></p>
                    <p>Views: <span class="font-semibold">3000</span></p>
                </div>
                <div class="flex flex-col gap-2 items-left md:ml-3 md:mr-3 md:max-w-fit">
                    <p>Quantity Left: <span class="font-semibold">50</span></p>
                    <p>Disputes: <span class="font-semibold text-green-600 text-sm">Won(34)</span>/<span
                            class="font-semibold text-red-600 text-sm">Lost(38)</span></p>
                    <p>Category: <span class="font-semibold text-sm">{{ $product->subCategory->name }}</span></p>
                </div>
                <div class="flex flex-col gap-2 items-left">
                    <p>Payment: <span
                        class="{{ $product->payment_type === 'FE' ? 'text-red-500 animate-pulse' : 'text-blue-500' }} font-semibold text-1xl">
                        {{ '{' . $product->payment_type . '}' }}
                    </span>
                </p>                
                    <p>Reviews: <a href="{{ $product->payment_url }}">
                            (<span class="text-green-600 font-semibold p-1 rounded-md text-sm">30</span>-
                            <span class="text-gray-500 font-semibold p-1 rounded-md text-sm">40</span>-
                            <span class="text-red-500  font-semibold p-1 rounded-md text-sm">60</span>)
                        </a></p>
                    <p>Vendor: <a href="/store/show/{{ $product->store->store_name }}/{{ $product->store->created_at->timestamp }}/{{ $product->store_id }}" class="text-blue-500 hover:underline">Real
                            Products</a><span class="font-sans"> ( {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }} ⭐)</span></p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 items-right md:mt-1 ml-auto w-full md:max-w-fit">
            <p class="text-white text-2xl md:text-1xl bg-gray-800 px-2 py-1 rounded-md text-center group-hover:text-sm transition-all duration-300">
                {{ $user->fiat->symbol.(number_format((\App\Models\ApiCurrency::where('fiat', $user->fiat->abbr)->first()->rate * $product->price), 2)) }}
            </p>

            <a href="/listing/{{ $product->created_at->timestamp }}/{{ $product->id }}" class="bg-green-700 text-white px-2 mb-4 py-1 rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-green-300 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                Buy Now
            </a>
            
            
        </div>
            <form action="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/{{ $product->store->store_name }}/listing/{{ $product->created_at->timestamp }}/{{ $product->id }}" method="post" class="flex flex-col gap-4 items-right md:mt-1 ml-auto md:max-w-fit opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 w-full md:w-1/2">
                @csrf
                @if (!$user->favoriteListings->where('product_id', $product->id)->first())
            <button class="border-2 border-blue-600 px-2 py-1 rounded-md hover:border-blue-600 focus:outline-none focus:ring focus:border-blue-700 text-sm" name="listing_action" value="like">Like</button>
                    
            @else
            <p class=" bg-blue-600 px-2 py-1 text-center rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 text-sm">Liked</p>

                @endif
            <button class="bg-orange-600 text-white px-2 py-1 rounded-md text-sm hover:bg-orange-500 focus:outline-none focus:ring focus:border-green-300" name="listing_action" value="cart_plus">
                Add to Cart
            </button>
        </form>
    </div>
</div>

