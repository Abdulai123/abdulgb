<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $name }} > {{ $action }}</title>
    @if ($user->theme == 'dark')
    <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('market.white.css') }}">
<meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

<link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ asset('filter.css') }}">
</head>

<body>
    @include('User.navebar')

    <div class="container">
        <div class="dir" style="margin-top:1em">
            <div class="dir-div">
                <a href="{{ url()->previous() }}" style="font-size:.8rem;">back</a> }}---
                <span style="color: darkgreen; font-size:.8rem;">Your login phrase is
                    `{{ $user->login_passphrase }}`</span>
                    <span style="color: darkorange; font-size:.8rem;">~=~ Your Wallet Balance Is:
                        `${{ $user->wallet->balance }}`</span>
            </div>
    
            <div class="prices-div">
                <span>BTC/USD: <span class="usd"> ${{ session('btc') }}</span></span>
                <span>XMR/USD: <span class="usd">${{ session('xmr') }}</span></span>
            </div>
        </div>
        <div class="main-div" style="margin-top:0px">
            <div class="main-store-div">
                <div class="s-main-image">
                   
                    @php
                    $avatarKey = $store->avatar;
                @endphp
                <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                    class="background-img"> 
                    <div>
                        <div class="div-p">
                            <p class="store-name">{{ $store->store_name }}<span style="font-size: .5em;">Store 
                                @if ($store->is_verified)
                                <img src="data:image/png;base64,{{ $icon['verify'] }}" title="Verified Seller" width="30" />
                                @endif
                            </span>
                            </p>
                            <p class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
                                @php
                                $weightedAverage = \App\Models\Review::claculateStoreRating($store->id);
                                @endphp
                                {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐
                            </p>
                        </div>
                        <div style="margin-top: 0; display:flex; justify-content:space-around">

                            <span>S I N C E</span> <span>{{ $store->created_at->format('d F, Y') }}</span>
                        </div>
                        <div class="div-p">
                            <p>Status: <span class="{{ $store->status }}">{{ $store->status }}</span></p> |
                            <p>Sales: {{ $store->width_sales }}</p> |
                            <p>Disputes: [<span style="color: #28a745;">Won ({{ $store->disputes_won }})</span>/<span
                                    style="color:#dc3545;">Lost ({{ $store->disputes_lost }})</span>]</p>
                        </div>
                        <div class="div-p">
                            <p>Listings: {{ $store->products()->where('status', 'Active')->count() }}</p> |
                            <p>Favorited: {{ $store->StoreFavorited->count() }}</p>
                            <p style="border-bottom: 3px dotted green">Last Seen: {{ \Carbon\Carbon::parse($store->user->last_seen)->diffForHumans() }}</p>
                        </div>
                        <div class="div-p">
                            <p class="selling">Selling: <a href=""
                                    style="font-size: 15px;">{{ $store->selling }}</a></p>
                        </div>
                        <div class="div-p ship-from">
                            <p>
                                Ship From: <a href=""
                                    style="font-size: 15px; text-transform:uppercase;">{{ $store->ship_from }}</a>
                            </p>
                            <p>
                                Ship To: <a href=""
                                    style="font-size: 15px; text-transform:uppercase;">{{ $store->ship_to }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                <form action="" method="POST">
                    @csrf
                <div class="div-p" style="display: flex; gap: 1.3em; justify-content:center;">
                    <a href="/store/show/pgp/{{ $store->store_name }}/{{ $store->created_at->timestamp }}/{{ $store->id }}" class="input-listing">PGP Key
                    </a>

                    <a href="/store/show/message/{{ $store->store_name }}/{{ $store->created_at->timestamp }}/{{ $store->id }}" class="input-listing">
                        Message</a>
                    @if ($user->favoriteStores->count() > 0)
                        @php $storeFavorited = false; @endphp
                        @foreach ($user->favoriteStores as $favoriteStore)
                            @if ($favoriteStore->store_id === $store->id)
                                <a href="/favorite/f_store" class="input-listing">
                                    UnFavorite</a>
                                @php $storeFavorited = true; @endphp
                            @endif
                        @endforeach

                        @if (!$storeFavorited)
                            <input type="submit" name="favorite_store" class="input-listing" value="Favorite Store">
                        @endif
                    @else
                        <input type="submit" name="favorite_store" class="input-listing" value="Favorite Store">
                    @endif
                    <a href="/store/show/reviews/{{ $store->store_name }}/{{ $store->created_at->timestamp }}/{{ $store->id }}" class="input-listing">
                        Reviews({{ $store->reviews->count() }}) </a><span class="last">

                    <input type="submit" name="block_store" value="Block Store" class="input-listing">

                    <a href="/report/store/{{ $store->store_name }}/{{ $store->created_at->timestamp }}/{{ $store->id }}" class="input-listing"
                            style="margin:1em"> Report</a>
                    
                </div>
            </form>
            @foreach ($user->favoriteStores as $favoriteStore)
            @if ($favoriteStore->store_id === $store->id)
                <p style="color: green; margin: .3em; font-weight: lighter; text-align:center;">This store is one
                    of
                    your favorite store!</p>
            @endif
        @endforeach
                <div class="bio">
                    @if (session('showpgp'))
                        <h3>{{ $store->store_name }} > PGP KEY</h3>
                        <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $store->user->pgp_key }}</textarea>
                    @else
                    <h3>{{ $store->store_name }} > Descriptions</h3>
                        <p>{{ $store->store_description }}</p>
                    @endif
                </div>
            </div>
            <div class="listing-name">
                <h3> {{ $store->store_name }} > Listings</h3>
            </div>
            <div class="products-grid">
                @forelse ($store->products()->where('status', 'Active')->paginate(30) as $product)
                    @include('User.products')
                @empty
                    No product found.
                @endforelse

            </div>
            {{ $store->products()->where('status', 'Active')->paginate(30)->render('vendor.pagination.custom_pagination') }}
        </div>
    </div>

    @include('User.footer')
</body>

</html>
