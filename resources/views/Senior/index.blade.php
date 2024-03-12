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
    <meta http-equiv="refresh" content="{{ session('session_timer') }};url=/senior/staff/kick/{{ $user->public_name }}/out">

    <link rel="stylesheet" href="{{ asset('market.white.css') }}">
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
                                <p><span>Status: </span> <span class="{{ $user->status }}">{{ $user->status }}</span></p>
                                <p><span>Role: </span> <span class="{{ $user->role }}">{{ $user->role }}
                                        Moderator</span></p>
                                        <p>}}--- <span style="color: darkgreen; font-size:.8rem;">Your login phrase is
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
                                <a href="/senior/staff/{{ $user->public_name }}/show/dashboard">Dashboard</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['group'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/users">Users({{ \App\Models\User::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['new_store'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/new stores">New
                                    Stores({{ \App\Models\NewStore::count() }})</a>
                            </div>

                            <div class="listings">
                                <img src="data:image/png;base64,{{ $icon['category'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/categories">Categories</a>
                            </div>
                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['add-store'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/stores">Stores({{ \App\Models\Store::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/products">Products({{ \App\Models\Product::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <div class="all-products">
                                <img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/disputes">Disputes({{ \App\Models\Dispute::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['plane-tickets'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/support">Support({{ \App\Models\Support::where('status', '!=', 'closed')->count() }})</a>
                            </div>

                            <div class="support">
                                <img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/reports">Reports({{ \App\Models\Report::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['partnership'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/waivers">Waivers({{ \App\Models\Waiver::count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['unauthorized'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/unauthorizes">Unauthorizes({{ \App\Models\Unauthorize::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter"
                                    width="25">
                                <a
                                    href="/senior/staff/{{ $user->public_name }}/show/reviews">Reviews({{ \App\Models\Review::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>

                            <hr>Supports, reports, settings...
                            <hr>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['shield'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/pgp">2FA PGP KEY</a>
                            </div>

                            <div class="settings">
                                <img alt="🖇️" style="font-size:1.5em; margin-right: .5em;" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/url">Private URL Link</a>
                            </div>

                            <div class="settings">
                                @if ($user->theme == 'white')
                                    <img src="data:image/png;base64,{{ $icon['night-mode'] }}" class="icon-filter"
                                        width="25">
                                    <a href="/senior/staff/{{ $user->public_name }}/show/theme">Dark Mode</a>
                                @else
                                    <img src="data:image/png;base64,{{ $icon['brightness'] }}" class="icon-filter"
                                        width="25">
                                    <a href="/senior/staff/{{ $user->public_name }}/show/theme">Light Mode</a>
                                @endif

                            </div>

                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['faq'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/faq">FAQ</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['web-coding'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/bugs">Bugs({{ \App\Models\Bug::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
                            </div>
                            <div class="wallet">
                                <img src="data:image/png;base64,{{ $icon['document'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/canary">Canary</a>
                            </div>
                            <div class="settings">
                                <img src="data:image/png;base64,{{ $icon['functions'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/functions">Market Functions</a>
                            </div>



                            <div class="settings" style="border-top: 2px solid gray;">
                                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter"
                                    width="25">
                                <a href="/senior/staff/{{ $user->public_name }}/show/news">News({{ \App\Models\News::whereDate('created_at', Carbon\Carbon::today())->count() }})</a>
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
                        @elseif($action === 'new stores')
                            @include('Senior.new_stores')
                        @elseif($action === 'categories')
                            @include('Senior.categories')
                        @elseif($action === 'disputes')
                            @include('Senior.disputes')
                        @elseif($action === 'products')
                            @include('Senior.products')
                        @elseif($action === 'settings')
                            @include('Senior.settings')
                        @elseif($action === 'view')
                            @include('Store.productView')
                        @elseif($action === 'notifications')
                            @include('Senior.notifications')
                        @elseif($action == 'messages')
                            @include('Senior.messages')
                        @elseif($action == 'stores')
                            @include('Senior.stores')
                        @elseif($action === 'news')
                            @include('Senior.news')
                        @elseif($action === 'rules')
                            @include('Senior.rules')
                        @elseif($action === 'support')
                            @include('Senior.support')
                        @elseif($action === 'reports')
                            @include('Senior.reports')
                        @elseif($action === 'waivers')
                            @include('Senior.waivers')
                        @elseif($action === 'url')
                            @include('Senior.mirror')
                        @elseif($action === 'canary')
                            @include('Senior.canary')
                        @elseif($action === 'pgp')
                            @include('Senior.twofa')
                        @elseif($action === 'faq')
                            @include('Senior.faq')
                            @elseif($action === 'wallet')
                            @include('Senior.wallet')
                        @elseif($action === 'bugs')
                            @include('Senior.bugs')
                        @elseif($action === 'unauthorizes')
                            @include('Senior.unauthorize')
                            @elseif($action === 'functions')
                            @include('Senior.functions')
                            @elseif($action === 'deposit')
                            @include('Senior.deposit')
                        @elseif($action === 'withdraw')
                            @include('Senior.withdraw')


                            {{-- Single display of actions --}}
                        @elseif($action === 'Show User')
                            @include('Senior.user')
                        @elseif($action === 'New Store')
                            @include('Senior.new_store')
                        @elseif($action === 'Store')
                            @include('Senior.store')
                        @elseif($action === 'Waiver')
                            @include('Senior.waiver')
                        @elseif($action === 'product')
                            @include('Senior.product')
                        @elseif($action === 'unauthorize')
                            @include('Senior.unauthorizeReview')
                            @elseif($action === 'modmail')
                            @include('Senior.startNewMessages')
                            @elseif($action === 'reviews')
                            @include('Senior.reviews')
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
