<h1>Market Products ({{ \App\Models\Product::all()->count() }})</h1>
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
        <form action="/senior/staff/{{ $user->public_name }}/show/products/search" method="get" style="text-align: center">
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

<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Store</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $products = session('products') ?? \App\Models\Product::orderByDesc('created_at')->paginate(50);
        @endphp
        @forelse ($products as $product)
            
            <tr>
                <td>#{{ $product->id }}</td>
                <td>{{ Str::limit($product->product_name, 20, '...') }}</td>
                <td>{{ $product->store->store_name }}</td>
                <td>{{ $product->created_at->DiffForHumans() }}</td>
                <td class="{{ $product->status }}">{{ $product->status }}</td>
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ Crypt::encrypt($product->id) }}">


                        <a href="/senior/staff/show/product/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                            style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>

                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No product found...</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $products->render('vendor.pagination.custom_pagination') }}
