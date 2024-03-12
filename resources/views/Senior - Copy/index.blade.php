<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ @asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
    <title>Whales Market | {{ $action != null ? $action : $user->public_name . ' Moderator' }}</title>
</head>

<body>
    @include('Senior.naveBar')
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
                                        $avatarKey = $user->avater;
                                    @endphp

                                    <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                                        class="background-img">

                                </div>
                            </div>
                            <div class="name-status">
                                <p>Welcome, {{ $user->public_name }}</p>
                                <p><span>Last Updated: </span>
                                    <span>{{ $user->updated_at->diffForHumans() }}</span>
                                </p>
                                <p><span>Member Since:
                                    </span><span>{{ $user->created_at->format('j F Y') }}</span>
                                </p>
                                <p><span>Status: </span> <span class="status-active">{{ $user->status }}</span></p>
                                <p><span>Role: </span> <span class="{{ $user->role }}">{{ $user->role }} Moderator</span></p>

                            </div>

                        </div>
                        <div class="menus">
                            <div class="dashboard">
                                <img src="data:image/png;base64,{{ $icon['dashboard'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/dashboard">Dashboard</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['group'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/users">Users()</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['add-store'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/stores">Stores()</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['category'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/categories">Categories</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/disputes">Disputes</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/products">Products</a>
                            </div>
                            <div class="reviews-a">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/reviews">Reviews</a>
                            </div>
                            <div class="reviews-a">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/share-access">Share Access</a>
                            </div>
                            <div class="orders">
                                <img src="data:image/png;base64,{{ $icon['orders'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/orders">Orders(0)
                                </a>
                            </div>
                            {{-- <div class="orders">
                                <img src="data:image/png;base64,{{ $icon['bonus'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/affiliate">Affiliate</a>
                            </div> --}}

                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['ads'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/promotion">Promotion</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['faq'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/support">Support</a>
                            </div>

                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/reports">Reports</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['partnership'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/waivers">Waivers</a>
                            </div>

                            <div class="settings" style="border-top: 2px solid gray;">
                                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/news">News</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['wallet'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/wallet">Wallet</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['rules'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/rules">Rules</a>
                            </div>
                        </div>
                    </div>
                    <div class="cls-main">
                        @if ($action === 'settings')
                            @include('Senior.settings')
                        @elseif($action === 'users')
                            @include('Senior.users')

                        @elseif($action === 'stores')
                            @include('Senior.stores')

                        @elseif($action === 'categories')
                            @include('Senior.categories')

                        @elseif($action === 'disputes')
                            @include('Senior.disputes')

                        @elseif($action === 'products')
                            @include('Senior.products')
                            
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
                            @include('Senior.dashboard')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Senior.footer')
</body>

</html>
