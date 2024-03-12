<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 style="text-transform: capitalize"> {{ $action }} >
        Orders({{ $action != 'all' ? $user->orders->where('status', $action)->count() : $user->orders->count() }})
    </h1>
    <p> All closed disputes, completed orders, cancelled orders after 15 days will be automatically deleted.</p>
    <table>

        <thead >
            <tr>
                <th >Sort By</th>
                <th>Number Of Rows</th>
                <th>Status</th>
                <th class="px-6 py-3">Payment Type</th>
                <th class="px-6 py-3 sr-only">Action Button</th>
            </tr>
        </thead>
        <tbody>
            <form action="/{{ $user->public_name }}/orders/search" method="get" style="text-align: center">
                @csrf
                <tr class="border-b dark:bg-gray-800 dark:border-gray-700">
                    <td>
                        <select name="sort_by" id="sort_by">
                            <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="highest_quantity"
                                {{ old('sort_by') == 'highest_quantity' ? 'selected' : '' }}>Highest Quantities</option>
                            <option value="lowest_quantity" {{ old('sort_by') == 'lowest_quantity' ? 'selected' : '' }}>
                                Lowest Quantities</option>
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
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="dispute" {{ old('status') == 'dispute' ? 'selected' : '' }}>Dispute</option>
                            <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="dispatched" {{ old('status') == 'dispatched' ? 'selected' : '' }}>Dispatched
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </td>
                    <td>
                        <select name="payment_type" id="">
                            <option value="all" {{ old('payment_type') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="Escrow" {{ old('payment_type') == 'Escrow' ? 'selected' : '' }}>Escrow
                            </option>
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

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900">
            Our products
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
        </caption>
        <thead class="text-xs text-gray-700 border-b uppercase dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">Item</th>
                <th class="px-6 py-3">Cost Per Item</th>
                <th class="px-6 py-3">Quantity</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Ordered Date</th>
                <th class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @php

                $orders = session('orders') ?? $user->orders->sortByDesc('updated_at');
            @endphp

            @forelse ($orders as $order)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><a
                            href="/listing/{{ $order->product->created_at->timestamp }}/{{ $order->product_id }}">#WM{{ $order->product->created_at->timestamp }}</a>
                    </td>
                    <td class="px-6 py-4">${{ $order->product->price }}</td>
                    <td class="px-6 py-4">{{ $order->quantity }}</td>
                    <td class="px-6 py-4">{{ $order->status }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
                    <td class="px-6 py-4"><a href="/order/{{ $order->created_at->timestamp }}/{{ $order->id }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">view</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan='7' class="px-6 py-4">No {{ session('orders') != null ? old('status') : "" }} order found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


