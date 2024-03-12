<div class="latest-orders">
    @if (session('success'))
        <p style="color: green; text-align:center; margin:0px;">{{ session('success') }}</p>
    @endif
    <div class="title-latest">
        <h4>NEWEST STORE REQUEST ({{ $new_stores->count() }})</h4>
        <div class="view-latest">
            <a href="/senior/staff/{{ $user->public_name }}/show/new stores">VIEW ALL</a>
        </div>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Store Name</th>
                    <th>Created At</th>
                    <th>Owner Last Seen</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dashboard_new_stores as $new_store)
                    <tr>
                        <td>#{{ $new_store->id }}</td>
                        <td>{{ $new_store->store_name }}</td>
                        <td>{{ $new_store->created_at->DiffForHumans() }}</td>
                        <td>{{ \Carbon\Carbon::parse($new_store->user->last_seen)->diffForHumans() }}</td>
                        <td class="{{ $new_store->user->store_status }}">{{ $new_store->user->store_status }}</td>
                        <td>
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="new_store_id" value="{{ Crypt::encrypt($new_store->id) }}">

                                <a href="/senior/staff/show/new store/{{ $new_store->created_at->timestamp }}/{{ $new_store->id }}"
                                    style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">Review</a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="top-products">
    <div class="title-latest">
        <h4>NEWEST PRODUCTS ({{ $products->count() }})</h4>
        <div class="view-latest">
            <a href="/senior/staff/{{ $user->public_name }}/show/products">VIEW ALL</a>
        </div>
    </div>
    <div>
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
                @foreach ($dashboard_products as $product)
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
                                    style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">Review</a>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
</div>
