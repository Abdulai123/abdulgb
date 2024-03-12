<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ @asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
    <title>Whales Market | {{ $action != null ? $action : $store->store_name . ' Store' }}</title>
</head>

<body>
    @include('Store.naveBar')
    <div class="container">
        <div class="main-div">
            <div class="notific-container" style="padding: 5px; margin:0px">
                <div class="cls-top">
                </div>
                <div class="main">
                    <div class="cls-left">
                        <div class="wlc-info">
                            <div class="avater">
                                <div class="bg-img">
                                    @php
                                        $avatarKey = $store->avatar;
                                    @endphp

                                    <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                                        class="background-img">

                                </div>
                            </div>
                            <div class="name-status">
                                <p>Welcome, {{ $store->store_name }}</p>
                                <p><span>Last Updated: </span>
                                    <span>{{ $store->updated_at->diffForHumans() }}</span>
                                </p>
                                <p><span>Member Since:
                                    </span><span>{{ $store->created_at->format('j F Y') }}</span>
                                </p>
                                <p class="span3" style="border: 2px solid skyblue; border-radius:.5rem; padding:5px;">
                                    @php
                                        $weightedAverage = \App\Models\Review::claculateStoreRating($store->id);
                                    @endphp
                                    {{ $weightedAverage != 0 ? $weightedAverage : '5.0' }}⭐
                                </p>
                                <p><span>Status: </span> <span class="status-active">{{ $store->status }}</span></p>
                            </div>

                        </div>
                        <div class="menus">
                            <div class="dashboard">
                                <img src="data:image/png;base64,{{ $icon['dashboard'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/dashboard">Dashboard</a>
                            </div>
                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['add'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/add-listings">Add Listings</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/products">Products</a>
                            </div>
                            <div class="reviews-a">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/reviews">Reviews</a>
                            </div>
                            <div class="reviews-a">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/share-access">Share Access</a>
                            </div>
                            <div class="orders">
                                <img src="data:image/png;base64,{{ $icon['orders'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/orders">Orders(
                                    @if (count($store->orders->where('status', '!=', 'completed')->where('status', '!=', 'cancelled')) > 0)
                                        <span
                                            class="unread">{{ count($store->orders->where('status', '!=', 'completed')->where('status', '!=', 'cancelled')) }}</span>
                                    @else
                                        <span class="read">0</span>
                                    @endif
                                    )
                                </a>
                            </div>
                            <div class="orders">
                                <img src="data:image/png;base64,{{ $icon['monitoring'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/stats">Store Stats</a>
                            </div>
                            <div class="orders">
                                <img src="data:image/png;base64,{{ $icon['bonus'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/affiliate">Affiliate</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['wallet'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/wallet">Wallet</a>
                            </div>
                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['ads'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/promotion">Promotion</a>
                            </div>
                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['coupon'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/coupons">Coupons</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['faq'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/support">Support</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/news">News</a>
                            </div>
                            <div class="settings" style="border-top: 2px solid gray;">
                                <img src="data:image/png;base64,{{ $icon['rules'] }}" class="icon-filter"
                                    width="25">
                                <a href="/store/{{ $store->store_name }}/show/rules">Rules</a>
                            </div>
                        </div>
                    </div>
                    <div class="cls-main">
                        @if ($action === 'settings')
                            @include('Store.settings')
                        @elseif($action === 'add-listings')
                            @include('Store.createListing')
                        @elseif($action === 'physical')
                            @include('Store.physical')
                        @elseif($action === 'digital')
                            @include('Store.digital')
                        @elseif($action === 'wallet')
                            @include('Store.wallet')
                        @elseif($action === 'support')
                            @include('Store.support')
                        @elseif($action === 'settings')
                            @include('Store.settings')
                        @elseif($action === 'products')
                            @include('Store.products')
                        @elseif($action === 'edit-product')
                            @include('Store.editlisting')
                        @elseif($action === 'view')
                            @include('Store.productView')
                        @elseif($action === 'notifications')
                            @include('Store.notifications')
                        @elseif($action == 'messages')
                            @include('Store.messages')
                        @elseif($action === 'orders')
                            @include('Store.orders')
                        @elseif($action === 'stats')
                            @include('Store.stats')
                        @elseif($action === 'affiliate')
                            @include('Store.affiliate')
                        @elseif($action === 'preview-order')
                            @include('Store.orderView')
                        @elseif($action === 'reply-review')
                            @include('Store.reply')
                        @elseif($action === 'reviews')
                            @include('Store.reviews')
                        @elseif($action === 'promotion')
                            @include('Store.promotion')
                        @elseif($action === 'coupons')
                            @include('Store.coupons')
                        @elseif($action === 'share-access')
                            @include('Store.share')
                        @elseif($action === 'news')
                            @include('Store.news')
                        @elseif($action === 'rules')
                            @include('Store.rules')
                            @elseif($action === 'messageUser')
                            @include('Store.messageUser')
                        @else
                            @include('Store.dashboard')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Store.footer')
</body>

</html>
