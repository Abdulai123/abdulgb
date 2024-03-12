<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif
    {{-- <link rel="stylesheet" href="{{ asset('auth.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/whales/admin/kick/{{ $user->public_name }}/out">
    <title>Whales Market | {{ $action != null ? $action : $user->public_name . '  Admin' }}</title>
</head>

<body>
    @include('Admin.naveBar')
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
                                <p><span>Status: </span> <span class="{{ $user->status }}">{{ $user->status }}</span>
                                </p>
                                <p><span>Role: </span> <span class="{{ $user->role }}">{{ $user->role }}</span>
                                </p>
                                <p>}}---<span style="color: darkgreen; font-size:.8rem;">Your login phrase is
                                        `{{ $user->login_passphrase }}`</span></p>
                                <p style="border-bottom: 3px dotted green">Last Seen:
                                    {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</p>
                                    <div class="prices-div">
                                        <span>BTC/USD: <span class="usd"> ${{ session('btc') }}</span></span>
                                        <span>XMR/USD: <span class="usd">${{ session('xmr') }}</span></span>
                                    </div>
                            </div>

                        </div>
                        <div class="menus">
                            <div class="dashboard">
                                <img src="data:image/png;base64,{{ $icon['dashboard'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/dashboard">Dashboard</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['group'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/users">Users({{ \App\Models\User::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['new_store'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/new stores">New
                                    Stores({{ \App\Models\NewStore::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['add-store'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/stores">Stores({{ \App\Models\Store::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['partnership'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/waivers">Stores
                                    Waivers({{ \App\Models\Waiver::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['category'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/categories">Categories</a>
                            </div>

                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/disputes">Disputes({{ \App\Models\Dispute::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/products">Products({{ \App\Models\Product::where('status', 'Pending')->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['orders'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/orders">Orders({{ \App\Models\Order::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['shopping-cart'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/carts">Carts({{ \App\Models\Cart::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['wallet'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/conversations">Conversations({{ \App\Models\Conversation::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['escrow'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/escrows">Escrows({{ \App\Models\Escrow::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>


                            <div class="settings" style="border-top: 2px solid gray;">
                                <img src="data:image/png;base64,{{ $icon['coupon'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/coupons">Coupons
                                    Codes({{ \App\Models\Promocode::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/reviews">Reviews({{ \App\Models\Review::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['partnership'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/share_accesses">Share
                                    Accesses({{ \App\Models\ShareAccess::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['feature'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/featureds">Featureds
                                    Listings({{ \App\Models\Featured::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <hr>
                            Infos
                            <hr>



                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['plane-tickets'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/support">Supports({{ \App\Models\Support::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['faq'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/faqs">FAQs({{ \App\Models\FAQ::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/news">News({{ \App\Models\News::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/reports">Reports({{ \App\Models\Report::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['unauthorized'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/unauthorize">Unauthorize({{ \App\Models\Unauthorize::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['web-coding'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/whales/admin/{{ $user->public_name }}/show/bugs">Bugs({{ \App\Models\Bug::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <hr>
                            market actions
                            <hr>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['server'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/servers">Servers</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['shield'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/pgp">2FA PGP KEY</a>
                            </div>

                            <div class="settings">
                                <img alt="🖇️" style="font-size:1.5em; margin-right: .5em;" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/mirrors">Mirrors</a>
                            </div>

                            <div class="settings">
                                @if ($user->theme == 'white')
                                    <img src="data:image/png;base64,{{ $icon['night-mode'] }}" class="icon-filter"
                                        width="25">
                                    <a href="/whales/admin/{{ $user->public_name }}/show/theme">Dark Mode</a>
                                @else
                                    <img src="data:image/png;base64,{{ $icon['brightness'] }}" class="icon-filter"
                                        width="25">
                                    <a href="/whales/admin/{{ $user->public_name }}/show/theme">Light Mode</a>
                                @endif

                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['notifications_type'] }}"
                                    class="icon-filter" width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/notifications_types">Notification
                                    Types</a>
                            </div>

                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['document'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/canary">Canary</a>
                            </div>

                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['functions'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/functions">Market Functions</a>
                            </div>

                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['wallet'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/wallets">Wallets</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['rules'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/rules">Rules</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['logs'] }}" class="icon-filter"
                                    width="25">
                                <a href="/whales/admin/{{ $user->public_name }}/show/payout">PayOut Logs</a>
                            </div>
                        </div>
                    </div>
                    <div class="cls-main">
                        @if ($action === 'settings')
                            @include('Admin.settings')
                        @elseif($action === 'users')
                            @include('Admin.users')
                        @elseif($action === 'stores')
                            @include('Admin.stores')
                        @elseif($action === 'new stores')
                            @include('Admin.new_stores')
                        @elseif($action === 'categories')
                            @include('Admin.categories')
                        @elseif($action === 'disputes')
                            @include('Admin.disputes')
                        @elseif($action === 'orders')
                            @include('Admin.orders')
                        @elseif($action === 'wallets')
                            @include('Admin.wallets')
                        @elseif($action === 'carts')
                            @include('Admin.carts')
                        @elseif($action === 'conversations')
                            @include('Admin.conversations')
                        @elseif($action === 'escrows')
                            @include('Admin.escrows')
                        @elseif($action === 'coupons')
                            @include('Admin.coupons')
                        @elseif($action === 'share_accesses')
                            @include('Admin.share_accesses')
                        @elseif($action === 'reviews')
                            @include('Admin.reviews')
                        @elseif($action === 'products')
                            @include('Admin.products')
                        @elseif($action === 'settings')
                            @include('Admin.settings')
                        @elseif($action === 'view')
                            @include('Admin.productView')
                        @elseif($action === 'notifications')
                            @include('Admin.notifications')
                        @elseif($action == 'messages')
                            @include('Admin.messages')
                        @elseif($action === 'news')
                            @include('Admin.news')
                        @elseif($action === 'rules')
                            @include('Admin.rules')
                        @elseif($action === 'support')
                            @include('Admin.support')
                        @elseif($action === 'reports')
                            @include('Admin.reports')
                        @elseif($action === 'waivers')
                            @include('Admin.waivers')
                        @elseif($action === 'servers')
                            @include('Admin.servers')
                        @elseif($action === 'faqs')
                            @include('Admin.faq')
                        @elseif($action === 'notifications_types')
                            @include('Admin.notificationTypes')
                        @elseif($action === 'functions')
                            @include('Admin.functions')
                        @elseif($action === 'bugs')
                            @include('Admin.bugs')
                        @elseif($action === 'canary')
                            @include('Admin.canary')
                            @elseif($action === 'payout')
                            @include('Admin.payout')
                            @elseif($action === 'pgp')
                            @include('Admin.pgp')
                            @elseif($action === 'deposit')
                            @include('Admin.deposit')
                            @elseif($action === 'withdraw')
                            @include('Admin.withdraw')


                            {{-- Single display of actions --}}
                        @elseif($action === 'Show User')
                            @include('Admin.user')
                        @elseif($action === 'New Store')
                            @include('Admin.new_store')
                        @elseif($action === 'Store')
                            @include('Admin.store')
                        @elseif($action === 'Waiver')
                            @include('Admin.waiver')
                        @elseif($action === 'product')
                            @include('Admin.product')
                        @elseif($action === 'Order')
                            @include('Admin.order')
                        @elseif($action === 'message')
                            @include('Admin.message')
                        @elseif($action === 'ticket')
                            @include('Admin.ticket')
                        @elseif($action === 'unauthorize')
                            @include('Admin.unauthorizeReview')
                        @elseif($action === 'modmail')
                            @include('Admin.modsMessage')
                            @elseif($action === 'mirrors')
                            @include('Admin.mirrors')
                        @else
                            @include('Admin.dashboard')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Admin.footer')
</body>

</html>
