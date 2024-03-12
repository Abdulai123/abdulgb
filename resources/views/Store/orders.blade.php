<h3 style="text-align: center;">{{ $store->store_name }} > All Orders({{ $store->orders->count() }})</h3>
<p class="notifications-p"> All closed disputes, completed orders, cancelled orders after 15 days will be automatically deleted.</p>
@if (session('success'))
    <p style="color: green; text-align:center; margin:0px;">{{ session('success') }}</p>
@endif


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
        <form action="/store/{{ $store->store_name }}/show/orders/search" method="get" style="text-align: center">
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

<div class="latest-orders">
    <div>
        <table>
            <thead>
                <tr>
                    <th>Listing</th>
                    <th>Buyer</th>
                    <th>Items</th>
                    <th>Updated</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    if (session()->has('orders')) {
                        $orders = session('orders');
                        $pag = false;
                    } else {
                        $orders = $store->orders()->paginate(50)->sortByDesc('updated_at');
                        $pag = true;
                    }
                    
                @endphp
                @forelse ($orders as  $order)
                    <tr>

                        <td>{{ Str::limit($order->product->product_name, 30, '...') }}</td>
                        <td>{{ $order->user->public_name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->updated_at->diffForHumans() }}</td>
                        <td class="{{ $order->product->payment_type}}">{{ '{'.$order->product->payment_type.'}' }}</td>
                        <td class="{{ $order->status }}">{{ $order->status }}</td>
                        <td>
                            <form action="/store/{{ $store->store_name }}/do" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ Crypt::encrypt($order->id) }}">

                                @if ($order->status == 'pending')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="accept">Accept</button>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="cancel">Cancel</button>
                                @elseif($order->status == 'processing' && $order->product->product_type == 'physical')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="shipped">Shipped</button>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="dispute">Dispute</button>
                                @elseif($order->status == 'processing' && $order->product->product_type == 'digital')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="sent">Sent</button>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="dispute">Dispute</button>
                                @elseif($order->status == 'shipped')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="delivered">Delivered</button>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="dispute">Dispute</button>
                                @elseif($order->status == 'sent' || $order->status == 'delivered' || $order->status == 'dispatched')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <button type="submit"
                                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                        name="dispute">Dispute</button>
                                @elseif($order->status == 'dispute')
                                    <a href="/store/{{ $store->store_name }}/show/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        style="font-size: .7rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">See
                                        dispute</a>
                                @elseif($order->status == 'completed')
                                    Order Completed
                                @elseif($order->status == 'cancelled')
                                    Order Cancelled
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Looks like you don't have any order yet or the search you provided.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($pag)
{{ $store->orders()->paginate(50)->render('vendor.pagination.custom_pagination') }}
@endif

