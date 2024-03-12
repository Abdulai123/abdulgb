<p style="text-align: center">Market Products ({{ $products->count() }})</p>
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
        @foreach ($products as $product)
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

                        @if ($product->status == 'Pending' || $product->status == 'pending')
                            <a href="/senior/staff/show/product/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>

                                {{-- <button type="submit"
                                style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="approve">Approve</button>

                                <button type="submit"
                                style="font-size: .7rem; background-color: red; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="reject">Reject</button> --}}

                        @elseif($product->status == 'banned')
                            <a href="/senior/staff/show/product/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                                style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="reject">Reject</button>

                        @else
                        <a href="/senior/staff/show/product/{{ $product->created_at->timestamp }}/{{ $product->id }}"
                            style="font-size: .7rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;">View</a>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- {{ $products->paginate(50)->render('vendor.pagination.custom_pagination') }} --}}
