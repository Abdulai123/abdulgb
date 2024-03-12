<h3 style="text-align: center;">{{ $store->store_name }} > All Orders({{ $store->orders->count() }})</h3>
@if (session('success'))
    <p style="color: green; text-align:center; margin:0px;">{{ session('success') }}</p>
@endif
<div class="latest-orders">
    <div>
        <table>
            <thead>
                <tr>
                    <th>Listing</th>
                    <th>Buyer</th>
                    <th>No. Items</th>
                    <th>Last Updated</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($store->orders()->paginate(100)->sortByDesc('created_at') as  $order)
                    <tr>

                        <td>{{ Str::limit($order->product->product_name, 30, '...') }}</td>
                        <td>{{ $order->user->public_name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->updated_at->diffForHumans() }}</td>
                        <td>{{ $order->product->product_type }}</td>
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
                                    style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                                    <a href="/store/{{ $store->store_name }}/show/messages/{{ $order->dispute->conversation->created_at->timestamp }}/{{ $order->dispute->conversation->id }}"
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
                        <td colspan="6">Looks like you don't have any order yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $store->orders()->paginate(100)->render('vendor.pagination.custom_pagination') }}

