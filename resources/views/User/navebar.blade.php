{{-- drop downs --}}

<div class="flex  justify-end mr-5">
    <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my orders" class="p-4 hover:text-orange-500 transition-colors duration-300 underline">My Orders</a>
    <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/supports" class="p-4 hover:text-orange-500 transition-colors duration-300 underline">Support</a>
    <a href="/canary.txt" class="p-4 hover:text-orange-500 transition-colors duration-300 underline">Canary & Keys</a>
    <a href="" class="p-4 hover:text-orange-500 transition-colors duration-300 underline group relative">Fiat
        Currency ({{ $user->fiat->abbr }} - {{ $user->fiat->symbol }})
        <form action="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/change_currency" method="post"
            class="big text-left opacity-0 border-2 rounded-md shadow-md absolute top-full right-0 transform-translate-x-2 translate-y-2 transition-all duration-300 invisible group-hover:opacity-100 group-hover:translate-y-1 group-hover:visible z-50 text-sm">
            @csrf

            <button type="submit" name="currency" value="USD"
                class="flex flex-row gap-2 w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">United
                States Dollar (USD) - $ {!! $user->fiat->abbr == 'USD' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="EUR"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Euro
                (EUR) - € {!! $user->fiat->abbr == 'EUR' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="JPY"
                class="w-full p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Japanese
                Yen (JPY) - ¥ {!! $user->fiat->abbr == 'JPY' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="GBP"
                class="w-full p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">British
                Pound Sterling (GBP) - £ {!! $user->fiat->abbr == 'GBP' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="CHF"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Swiss
                Franc (CHF) - CHF {!! $user->fiat->abbr == 'CHF' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="CAD"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Canadian
                Dollar (CAD) - $ {!! $user->fiat->abbr == 'CAD' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="AUD"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Australian
                Dollar (AUD) - A$ {!! $user->fiat->abbr == 'AUD' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="CNY"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Chinese
                Yuan (Renminbi) (CNY) - ¥ {!! $user->fiat->abbr == 'CNY' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="SEK"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">Swedish
                Krona (SEK) - kr {!! $user->fiat->abbr == 'SEK' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

            <button type="submit" name="currency" value="NZD"
                class="w-full  p-3 rounded-md mb-2 text-left text-sm hover:border-8 hover:border-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">New
                Zealand Dollar (NZD) - NZ$ {!! $user->fiat->abbr == 'NZD' ? '<span class="text-green-700 p-1 rounded-md text-sm">Active</span>' : '' !!}</button>

        </form>
    </a>
    <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/become a vendor" class="p-4 hover:text-orange-500 transition-colors duration-300 underline">Become a vendor</a>
    <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my wallet" class="p-4 hover:text-orange-500 transition-colors duration-300">Balance:
        <span class="text-green-700"> {{ $user->fiat->symbol.(number_format((\App\Models\ApiCurrency::where('fiat', $user->fiat->abbr)->first()->rate * $user->wallet->balance), 2)) }}</span></a>
</div>


{{-- Site name and logo on the left of the nav bar --}}
<div class="flex items-center justify-around mb-8">
    <div class="flex items-center ml-4">
        <a href="/">
            <img src="data:image/png;base64,{{ $icon['gb'] }}" alt="Grand Bazaar Logo"
                class="w-12 h-12 object-contain rounded-full border-2 border-orange-500 animate-bounce">
        </a>

        <div class="text-orange-500 ml-2">
            <a href="/"
                class="text-2xl md:text-4xl font-bold font-serif tracking-wide transition duration-300 hover:tracking-tighter">Grand
                Bazaar</a>
        </div>
    </div>

    <div class="group relative">

        <p
            class="p-2 md:p-2 hover:text-orange-500 transition-colors duration-300 border-gray-500 hover:border-gray-700 active:border-gray-800 border-2 rounded-md flex relative capitalize text-center">
            Categories
            <img src="data:image/png;base64,{{ $icon['categories'] }}" class="ml-2" width="25">
        </p>


        <!-- Hidden Content -->
        <div
            class="big opacity-0 border-2 rounded-md shadow-md absolute top-full  transform-translate-x-2 translate-y-2 transition-all duration-300 invisible group-hover:opacity-100 group-hover:translate-y-1 group-hover:visible z-40">

            <div class="flex flex-col gap-2 col-span-2 flex-grow w-96 p-4">

                @php  
                $nav_categories = \App\Models\Category::where('parent_category_id', null)->get();
                @endphp
                
                @foreach ($nav_categories as $category)
                    <a href="/parent/category/{{ $category->created_at->timestamp }}/{{ $category->id }}" value="{{ $category->id }}"
                        class="p-2 rounded-md hover:border-8 hover:border-gray-700 active:border-gray-800 transition-all duration-300 cursor-pointer flex justify-between hover:text-orange-500"><span
                            class="hover:text-orange-500">{{ $category->name }}</span><span
                            class="text-orange-500 font-medium text-xl">({{ \App\Models\Product::where('status', 'Active')->where('parent_category_id', $category->id)->count() }})</span></a>
                @endforeach
            </div>
        </div>
    </div>


    {{-- Search bar in the center of the nav bar --}}
    <div
        class="flex items-center border-2 rounded-md shadow-md border-gray-500 hover:border-gray-700 active:border-gray-800">
        <form action="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/listings/search" method="get" class="">
            @csrf
            <input type="text" name="search_listings" placeholder="Search for anything here..."
                class="flex-grow px-4 py-2 focus:outline-none focus:ring cursor-pointer">
            <button type="submit" id="Search"
                class="bg-blue-500 text-white px-6 py-2 hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Search
            </button>
        </form>
    </div>

    <div
        class="flex border-2 rounded-md p-2 cursor-pointer border-gray-500 hover:border-gray-700 active:border-gray-800">
        <img src="data:image/png;base64,{{ $icon['cart'] }}" class="ml-2" width="25">
        <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/cart">Cart (<span
                class="text-green-500">0</span>)</a>
    </div>

    {{-- User settings on the right of the nav bar --}}
    <div class="group relative">
        <!-- Link -->
        <a href="#"
            class="p-2 md:p-2 hover:text-orange-500 transition-colors duration-300 border-gray-500 hover:border-gray-700 active:border-gray-800 border-2 rounded-md flex relative capitalize text-center">
            <img src="data:image/png;base64,{{ $icon['user'] }}" class="mr-2" width="25">
            My Account
        </a>

        <!-- Hidden Content -->
        <div
            class="big opacity-0 border-2 rounded-md shadow-md absolute top-full right-0 transform-translate-x-2 translate-y-2 transition-all duration-300 invisible group-hover:opacity-100 group-hover:translate-y-1 group-hover:visible z-40">

            <!-- Your content here -->
            <div class="flex flex-col gap-2 flex-grow w-64 p-4">
                <p>You are logged in as <span class="text-green-600">'{{ $user->public_name }}'</span></p>
                <p>Your login passphrase is <span class="text-blue-500">'{{ $user->login_passphrase }}'</span></p>
                <hr>
                <a href="/notification"
                    class="hover:text-orange-500 transition-colors duration-300 underline">Notifications (0)</a>
                <a href="/messages" class="hover:text-orange-500 transition-colors duration-300 underline">Messages
                    (0)</a>
                <hr>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my orders" class="hover:text-orange-500 transition-colors duration-300 underline">My Orders</a>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my reviews" class="hover:text-orange-500 transition-colors duration-300 underline">My
                    Reviews</a>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my likes" class="hover:text-orange-500 transition-colors duration-300 underline">My Likes</a>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my block list" class="hover:text-orange-500 transition-colors duration-300 underline">My Block
                    List</a>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my wallet" class="hover:text-orange-500 transition-colors duration-300 underline">My Wallet</a>
                <a href="/{{ $user->public_name }}/{{ $user->created_at->timestamp }}/my setup" class="hover:text-orange-500 transition-colors duration-300 underline">My Setup</a>
                <a href="/logout" class="hover:text-orange-500 transition-colors duration-300 underline">Let Me Out
                    (LogOut)</a>

            </div>
        </div>
    </div>

</div>





{{-- <div class="end-menus">
        <div class="notification" title="shopping cart">
            <a href="/cart" target="" rel="noopener noreferrer"> <img
                    src="data:image/png;base64,{{ $icon['shopping-cart'] }}" class="icon-filter" width="25">
                <span
                    class="{{ $user->carts->count() > 0 ? 'cart-n-tr' : '' }}">{{ $user->carts->count() > 0 ? $user->carts->count() : '' }}</span>
            </a>
        </div>
        <div class="logout" title="open a store here">
            <a href="/open-store"> <img src="data:image/png;base64,{{ $icon['add-store'] }}" class="icon-filter"
                    width="25"></a>
        </div>
        <div class="my-hideout" title="user settings and control panel">
            <a href="#">Settings<img src="data:image/png;base64,{{ $icon['down-arrow'] }}" class="icon-filter"
                    width="25"></a>
            <ul class="hideout-menus">
                <li>
                    <h3>ACCOUNT</h3>
                    <ul>
                        <li><a href="/account/pgp"><img src="data:image/png;base64,{{ $icon['shield'] }}"
                                    class="icon-filter" width="25"> PGP KEY [2FA]</a></li>
                        <li><a href="/account/storeKey"><img src="data:image/png;base64,{{ $icon['partnership'] }}"
                                    class="icon-filter" width="25">Store key</a></li>
                        <li><a href="/account/changePassword"><img
                                    src="data:image/png;base64,{{ $icon['change-management'] }}" class="icon-filter"
                                    width="25"> Change
                                password</a></li>
                        <li><a href="/account/referral"><img src="data:image/png;base64,{{ $icon['bonus'] }}"
                                    class="icon-filter" width="25"> Referral Program</a></li>
                        <li><a href="/account/mirror">
                                <img src="" alt="🖇️" style="font-size:2em; margin-right:0px;"
                                    class="icon-filter"> Private URL Link</a></li>
                        <li><a href="/account/fiat_currency">
                                <img src="data:image/png;base64,{{ $icon['fiat'] }}" alt=""
                                    class="icon-filter" width="25">  Fiat Currency <span style="background-color: darkgreen; color:#f1f1f1; font-weight:800; padding: 2px; border-radius:5px; font-size:10px;"> New</span> </a></li>

                        <li><a href="/account/stats">
                                <img src="data:image/png;base64,{{ $icon['monitoring'] }}" alt=""
                                    style="font-size:2em; margin-right:0px;" class="icon-filter"> Your Stats</a></li>
                        <li>
                            <a href="/account/theme">
                                @if ($user->theme == 'white')
                                    <img src="data:image/png;base64,{{ $icon['night-mode'] }}" class="icon-filter"
                                        width="25">

                                    Dark Mode
                                @else
                                    <img src="data:image/png;base64,{{ $icon['brightness'] }}" class="icon-filter"
                                        width="25">

                                    Light Mode
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <h3>FAVORITES</h3>
                    <ul>
                        <li><a href="/favorite/f_store"><img src="data:image/png;base64,{{ $icon['thumbs-up-fav'] }}"
                                    class="icon-filter" width="25">Favorite stores</a>
                        </li>
                        <li><a href="/blocked/b_store"><img src="data:image/png;base64,{{ $icon['thumbs-down-'] }}"
                                    class="icon-filter" width="25">Blocked stores</a></li>
                        <li><a href="/favorite/f_listing"><img src="data:image/png;base64,{{ $icon['love'] }}"
                                    class="icon-filter" width="25">Favorite listings</a></li>
                    </ul>
                </li>
                <li>
                    <h3>WALLET</h3>
                    <ul>
                        <li><a href="/wallet/deposit"><img src="data:image/png;base64,{{ $icon['deposit'] }}"
                                    class="icon-filter" width="25"> Deposit</a></li>
                        <li><a href="/wallet/withdraw"><img src="data:image/png;base64,{{ $icon['withdraw'] }}"
                                    class="icon-filter" width="25"> Withdraw</a></li>
                    </ul>
                </li>
                <li>
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="/ticket"><img src="data:image/png;base64,{{ $icon['plane-tickets'] }}"
                                    class="icon-filter" width="25">Tickets</a>
                        </li>
                        <li><a href="/bugs"><img src="data:image/png;base64,{{ $icon['web-coding'] }}"
                                    class="icon-filter" width="25"> Report bugs</a></li>
                    </ul>
                </li>
                <li>
                    <h3>ORDERS</h3>
                    <ul>
                        <li><a href="/orders/all"><img src="data:image/png;base64,{{ $icon['orders'] }}"
                                    class="icon-filter" width="25"> All(<span
                                    style="color:blue">{{ $user->orders->count() }}</span>) </a></li>
                        <li><a href="/orders/pending"><img src="data:image/png;base64,{{ $icon['wall-clock'] }}"
                                    class="icon-filter" width="25"> Pending(<span
                                    style="color:blue">{{ $user->orders->where('status', 'pending')->count() }}</span>)</a>
                        </li>
                        <li><a href="/orders/dispatched"><img
                                    src="data:image/png;base64,{{ $icon['fast-delivery'] }}" class="icon-filter"
                                    width="25"> Dispatched(<span
                                    style="color:blue">{{ $user->orders->where('status', 'dispatched')->count() }}</span>)
                            </a></li>
                        <li><a href="/orders/completed"><img src="data:image/png;base64,{{ $icon['success'] }}"
                                    class="icon-filter" width="25"> Completed(<span
                                    style="color:blue">{{ $user->orders->where('status', 'completed')->count() }}</span>)
                            </a></li>
                        <li><a href="/orders/dispute"><img src="data:image/png;base64,{{ $icon['dispute'] }}"
                                    class="icon-filter" width="25">Disputed(<span
                                    style="color:blue">{{ $user->orders->where('status', 'dispute')->count() }}</span>)
                            </a></li>
                        <li><a href="/orders/cancelled"><img src="data:image/png;base64,{{ $icon['close'] }}"
                                    class="icon-filter" width="25">Cancelled(<span
                                    style="color:blue">{{ $user->orders->where('status', 'cancelled')->count() }}</span>)
                            </a></li>
                    </ul>
                </li>
                <li>
                    <h3>OTHERS</h3>
                    <ul>
                        <li><a href="/open-store"><img src="data:image/png;base64,{{ $icon['add-store'] }}"
                                    class="icon-filter" width="25"> Open store</a></li>
                        <li><a href="/canary"> <img src="data:image/png;base64,{{ $icon['document'] }}"
                                    class="icon-filter" width="25"> Canary & PGP</a></li>
                        <li><a href="/faq"><img src="data:image/png;base64,{{ $icon['faq'] }}"
                                    class="icon-filter" width="25"> F.A.Q</a></li>
                        @php
                            $unreadNews = 0;
                            $allNews = \App\Models\News::all();

                            foreach ($allNews as $news) {
                                $isUnread = true;

                                foreach ($user->newsStatuses as $status) {
                                    if ($status != null && $news->id == $status->news_id) {
                                        $isUnread = false;
                                        break;
                                    }
                                }

                                if ($isUnread) {
                                    $unreadNews += 1;
                                }
                            }
                        @endphp

                        <li><a href="/news"><img src="data:image/png;base64,{{ $icon['news'] }}"
                                    class="icon-filter" width="25"> News( <span
                                    style="color: {{ $unreadNews > 0 ? 'red' : '' }}; font-weight: {{ $unreadNews > 0 ? 'bold' : 'normal' }}"
                                    class="{{ $unreadNews > 0 ? 'blink' : '' }}">{{ $unreadNews }}</span> )</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="notification" title="messages">
            <a href="/messages" target="" rel="noopener noreferrer">
                <img src="data:image/png;base64,{{ $icon['mail'] }}" class="icon-filter" width="25">
                @php
                    $unread_messages = App\Models\MessageStatus::where('user_id', $user->id)
                        ->where('is_read', 0)
                        ->count();
                @endphp
                @if ($unread_messages > 0)
                    <span class="new-notification">{{ $unread_messages }}</span>
                @endif
            </a>
        </div>
        <div class="notification" title="notifications">
            <a href="/notification" target="" rel="noopener noreferrer">
                <img src="data:image/png;base64,{{ $icon['notification'] }}" class="icon-filter" width="25">
                @if ($user->notifications->where('is_read', 0)->count() > 0)
                    <span class="new-notification">{{ $user->notifications->where('is_read', 0)->count() }}</span>
                @endif
            </a>
        </div>
        <div class="logout" title="logout">
            <a href="/logout"> <img src="data:image/png;base64,{{ $icon['logout'] }}" class="icon-filter"
                    width="25"></a>
        </div>
    </div> --}}

{{-- <div class="rainbow-border border-blue-500 border-2 m-4"></div> --}}
{{-- categories --}}
{{-- 

<div>
    <ul class="flex items-center gap-5 mb-3 text-sm">

        @foreach ($parentCategories as $parent_category)
            <li><a href="/parent/category/{{ $parent_category->created_at->timestamp }}/{{ $parent_category->id }}"
                    class="hover:bg-orange-700 p-3 m-2 text-wrap text-3xs">
                    {{ $parent_category->name }}
                </a>

                <ul
                    class="opacity-0 border border-white p-8 mt-2 rounded-md shadow-md absolute top-full left-1/2 transform-translate-x-1/2 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                    @foreach ($subCategories as $sub_category)
                        @if ($parent_category->id === $sub_category->parent_category_id)
                            <li class="sub-cat-li"><a
                                    href="/sub/category/{{ $sub_category->created_at->timestamp }}/{{ $sub_category->id }}">
                                    <img src="data:image/png;base64,{{ $icon['right'] }}" class="icon-filter"
                                        width="25">
                                    {{ $sub_category->name }}</a>
                                <span
                                    class="count">{{ $sub_category->subProducts()->where('status', 'Active')->count() }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
<div class="rainbow-border border-blue-500 border-4 m-4"></div> --}}
