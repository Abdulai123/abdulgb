@php
    $allOrders = 0;
foreach (\App\Models\Store::all() as $store) {
    $allOrders += $store->width_sales;
}
@endphp
<h1 class="notifications-h1" style="text-transform: capitalize"> Orders({{ $orders->count() }}) > Total Orders({{ $allOrders }})</h1>


<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>Payment Type</th>
            <th>Action Button</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/orders/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="highest_quantity" {{ old('sort_by') == 'highest_quantity' ? 'selected' : '' }}>Highest Quantities</option>
                        <option value="lowest_quantity" {{ old('sort_by') == 'lowest_quantity' ? 'selected' : '' }}>Lowest Quantities</option>
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
                    <select name="status" id="status">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="dispute" {{ old('status') == 'dispute' ? 'selected' : '' }}>Dispute</option>
                        <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="dispatched" {{ old('status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>                
                <td>
                    <select name="payment_type" id="">
                        <option value="all" {{ old('payment_type') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="Escrow" {{ old('payment_type') == 'Escrow' ? 'selected' : '' }}>Escrow</option>
                        <option value="FE" {{ old('payment_type') == 'FE' ? 'selected' : '' }}>FE</option>
                    </select>
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
            <th>ID</th>
            <th>Cost Per Item</th>
            <th>Quantity</th>
            <th>Payment Type</th>
            <th>Store</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        @php
        if (session()->has('orders')) {
            $orders = session('orders');
            $pag = false;
        } else {
            $orders = \App\Models\Order::paginate(50)->sortByDesc('updated_at');
            $pag = true;
        }
        
    @endphp
    @forelse ($orders as  $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>${{ $order->product->price }}</td>
                <td>{{ $order->quantity }}</td>
                <td class="{{ $order->product->payment_type }}">{{ '{'.$order->product->payment_type.'}' }}</td>
                <td>{{ $order->store->store_name }}</td>
                <td class="{{ $order->status }}">{{ $order->status }}</td>
                <td>{{ $order->created_at->DiffForHumans() }}</td>
                <td><a href="/whales/admin/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}">view</a></td>
            </tr>
            @empty
            <tr>
                <td colspan='7'>No {{ $action }} order found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($pag)
{{ $store->orders()->paginate(50)->render('vendor.pagination.custom_pagination') }}
@endif