<h1 style="text-align: center;">Carts({{ $carts->count() }})</h1>

<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/carts/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="highest_quantity" {{ old('sort_by') == 'highest_quantity' ? 'selected' : '' }}>Highest Quantity</option>
                        <option value="lowest_quantity" {{ old('sort_by') == 'lowest_quantity' ? 'selected' : '' }}>Lowest Quantity</option>
                        <option value="product_highest" {{ old('sort_by') == 'product_highest' ? 'selected' : '' }}>Product Highest</option>
                        <option value="price_highest" {{ old('sort_by') == 'price_highest' ? 'selected' : '' }}>Price Highest</option>
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
                <td style="text-align: center; margin:0px; padding:0px;">
                    <input type="submit" class="submit-nxt" style="width: max-content; margin:0px; padding:.5em;"
                    value="Perform">
                </td>
            </tr>
        </form>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Extra/Shipping</th>
            <th>Price/Item</th>
            <th>Discount</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $carts = session('carts') ?? \App\Models\Cart::paginate(50);
        @endphp
        @forelse ($carts as $cart)
            <tr class="self-container">
                <td>#{{ $cart->id }}</td>
                <td>{{ $cart->user->public_name }}</td>
                <td>{{ Str::limit($cart->product->product_name, 3, '...') }}</td>
                <td>{{ $cart->quantity }}</td>
                <td>+${{ $cart->extraShipping->cost ?? '0.00' }}</td>
                <td>${{ $cart->product->price }}</td>
                <td>${{ $cart->discount }}</td>
                <td>{{ $cart->created_at->DiffForHumans() }}</td>
            </tr>
        </form>
    @empty

        <tr>
            <td colspan="10">
                <span class="no-notification">
                    Cart is currently empty.
                </span>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $carts->render('vendor.pagination.custom_pagination') }}
