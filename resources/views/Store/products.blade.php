<div style="text-align: center;">
    <a href="/store/{{ $store->store_name }}/show/add-listings" class="input-listing">Create new listing</a><br><br>
</div>
@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('success') }}</p>
@endif
@if (session('error') != null)
    <p style="text-align: center; background: darkred; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
        {{ session('error') }}</p>
@endif
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>Payment Type</th>
            <th>Search Term</th>
            <th>Action Button</th>
        </tr>
    </thead>
    <tbody>
        <form action="/store/{{ $store->store_name }}/show/products/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="popular" {{ old('sort_by') == 'popular' ? 'selected' : '' }}>Popular</option>
                        <option value="price_highest" {{ old('sort_by') == 'price_highest' ? 'selected' : '' }}>Price Highest</option>
                        <option value="price_lowest" {{ old('sort_by') == 'price_lowest' ? 'selected' : '' }}>Price Lowest</option>
                        <option value="oldest" {{ old('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </td>
                <td>
                    <select name="number_of_rows" id="number_of_rows">
                        <option value="50" {{ old('number_of_rows') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ old('number_of_rows') == '100' ? 'selected' : '' }}>100</option>
                        <option value="150" {{ old('number_of_rows') == '150' ? 'selected' : '' }}>150</option>
                        <option value="250" {{ old('number_of_rows') == '250' ? 'selected' : '' }}>250</option>
                    </select>
                </td>
                <td>
                    <select name="status" id="">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Paused" {{ old('status') == 'Paused' ? 'selected' : '' }}>Paused</option>
                    </select>
                </td>
                <td>
                    <select name="payment_type" id="">
                        <option value="all" {{ old('payment_type') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="Escrow" {{ old('payment_type') == 'Escrow' ? 'selected' : '' }}>Escrow</option>
                        <option value="FE" {{ old('payment_type') == 'FE' ? 'selected' : '' }}>FE</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="search_term" id="search_term" class="form-input"
                        placeholder="Type here the product name...." value="{{ old('search_term') }}">
                </td>
                <td style="text-align: center; margin:0px; padding:0px;">
                    <input type="submit" class="submit-nxt" style="width: max-content; margin:0px; padding:.5em;"
                        value="Search">
                </td>
            </tr>
        </form>
    </tbody>
</table>

<div class="products-grid" style="box-shadow: none; margin:0px; padding:0px;">

    @php
        $products = session('products');
    @endphp

    @if ($products && count($products) > 0)
        @foreach ($products as $product)
            @include('Store.product')
        @endforeach
    @elseif (empty($products))
        @php
            $defaultProducts = $store->products()->paginate(25);
        @endphp

        @foreach ($defaultProducts as $product)
            @include('Store.product')
        @endforeach

        {{ $defaultProducts->render('vendor.pagination.custom_pagination') }}
    @else
        <p style="margin: 0px; text-align:left;">No products listed yet or found for the search. -_-</p>
    @endif

</div>

