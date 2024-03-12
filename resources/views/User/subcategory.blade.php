<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $user->public_name }} > {{ $name }} > {{ $action }}</title>

    <link rel="stylesheet" href="{{ asset('market.css') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @include('User.navebar')

    <div class="flex p-2 gap-4">

        <!-- Left Side -->
        <div class="p-2">

            {{-- currency div --}}
            <div
                class="md:mb-14 lg:mb-14 p-2 rounded-md shadow-md border border-gray-500 hover:border-8 m-2  hover:border-gray-700 hover:text-gray-700  active:border-gray-800 active:text-gray-800 transition-all duration-300">

                <div class="flex flex-row items-start justify-around mb-2">
                    <span class="text-sm font-bold text-gray-300">Currency</span>
                    <span class="text-sm font-bold ml-4 text-gray-300">Rate/USD</span>
                    <span class="text-sm font-bold ml-4 text-yellow-400">BTC</span>
                    <span class="text-sm font-bold ml-4 text-purple-400">XMR</span>
                </div>
                @foreach (\App\Models\ApiCurrency::all() as $currency)
                    <div class="flex flex-row items-start justify-around mb-2 justify-start">
                        <span class="text-sm">{{ $currency->fiat }}</span>
                        <span class="text-sm">${{ $currency->rate }}</span>
                        <span class="text-sm text-yellow-500">{{ $currency->BTC }}</span>
                        <span class="text-sm text-purple-500">{{ $currency->XMR }}</span>
                    </div>
                @endforeach
            </div>



            {{-- search div --}}
            <div
                class="p-2 rounded-md shadow-md border border-gray-500 hover:border-8 m-2 hover:border-gray-700 hover:text-gray-700 active:border-gray-800  transition-all duration-300 bg-gray-900">
                <div class="categories search-div text-white">
                    <h3 class="font-bold mb-2 text-orange-500 text-sm">Advance Search Listings & Vendors</h3>

                    <form action="/search" method="get" class="search-form">
                        @csrf
                        <input type="text"
                            class="search_name p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300 w-full cursor-pointer"
                            name="pn" placeholder="Search for anything here..." value="">

                        <div class="flex items-center gap-4 m-2">
                            <label class="flex items-center">
                                <input type="radio" name="pt2" value="all" checked class="mr-1">
                                <span class="text-sm">All</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="pt2" value="digital" class="mr-1">
                                <span class="text-sm">Digital</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="pt2" value="physical" class="mr-1">
                                <span class="text-sm">Physical</span>
                            </label>
                        </div>

                        <div class="flex items-left">
                            <div class="ml-4">
                                <input type="checkbox" name="auto_shop" id="autoShop"
                                    class="h-4 w-4 text-blue-500  focus:ring focus:border-blue-300">
                                <label for="autoShop" class="ml-2 text-gray-300">AutoShop</label>
                            </div>

                            <div class="ml-4">
                                <input type="checkbox" name="desc" id="includeDesc"
                                    class="h-4 w-4 text-blue-500 focus:ring focus:border-blue-300">
                                <label for="includeDesc" class="ml-2 text-gray-300">Include Descriptions</label>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-2 flex-col">
                            <input type="number" name="pf" min="0" placeholder="Price: min $0.0"
                                id="minPrice"
                                class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300 cursor-pointer">
                            <input type="number" name="pt" min="0" placeholder="Price: max $0.0"
                                id="maxPrice"
                                class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300 cursor-pointer">
                        </div>

                        <div class="flex items-center mt-2">
                            <select name="filter-product" id="sortBy"
                                class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300 cursor-pointer">
                                <option value="">---Sort By---</option>
                                <option value="best-match">Best Match</option>
                                <option value="newest">Newest listed</option>
                                <option value="oldest">Oldest listed</option>
                                <option value="Cheapest">Price + Shipping: lowest first</option>
                                <option value="highest">Price + Shipping: highest first</option>
                            </select>
                        </div>


                        <div class="flex items-center mt-2">
                            <select name="str_n"
                                class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300">
                                <option value="">---Select Vendor---</option>
                                @forelse (\App\Models\Store::where('status', 'active')->get() as $store)
                                    <option value="{{ $store->store_name }}">{{ $store->store_name }}</option>
                                @empty
                                    <option value="">No vendor found.... ):</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="flex items-center mt-2">
                            <select name="sf" id=""
                                class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300">
                                <option value="">---Ship From: Any Country---</option>
                                @include('User.countries')
                            </select>
                        </div>

                        <div class="flex items-center mt-2">
                            <select name="st" id=""
                                class=" w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-300">
                                <option value="World Wide">---Ship To: World Wide---</option>
                                @include('User.countries')
                            </select>
                        </div>

                        <div class="flex gap-4 flex-col">
                            <div class="flex gap-1 flex-col mt-2">
                                <select name="parent_category"
                                    class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:border-blue-300 mb-2">
                                    <option value="">---Select Parent Category---</option>
                                    @foreach ($categories->where('parent_category_id', null) as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <select name="sub_category"
                                    class="w-full  border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                                    <option value="">---Select Sub Category---</option>
                                    @foreach ($categories->where('parent_category_id', '!=', null) as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-1">
                                <select name="payment_type"
                                    class="w-full  border border-gray-300 p-2 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                                    <option value="">---Payment System---</option>
                                    <option value="Escrow">Escrow</option>
                                    <option value="FE">Finalize Early</option>
                                </select>
                            </div>
                        </div>

                        <!-- ... (other form fields) ... -->

                        <div class="flex items-center gap-4 mt-2 justify-center">
                            <button type="submit"
                                class="w-full search-button bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>


        <!-- Right Side -->
    <!-- Right Side -->
    <div class="rounded-md shadow-md flex-grow flex-col gap-4">

        @forelse ($products as $product)
            @include('User.products')
        @empty
            No product found.
        @endforelse
        {{ $products->render('vendor.pagination.custom_pagination') }}
    </div>


    </div>

</body>

</html>
