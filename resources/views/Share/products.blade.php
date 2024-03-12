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
{{-- <table>
    <thead>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <form action="" method="post">
            <td>
                <select name="" id="">
                    <option value="">---Sort By---</option>
                </select>
            </td>
            <td>
                <select name="" id="">
                    <option value="">---Sort By---</option>
                </select>
            </td>
            <td>
                <select name="" id="">
                    <option value="">---Sort By---</option>
                </select>
            </td>
        </form>
    </tbody>
</table> --}}
<div class="products-grid" style="box-shadow: none; margin:0px; padding:0px;">
    @if (!empty($store->products))
    @foreach ($store->products()->paginate(50) as $product)
    @include('Store.product')
    @endforeach
       
            {{-- Custom Pagination Links --}}
    @else
      <P>Your have no product listed yet. -_-</P>
    @endif
</div>
{{ $store->products()->paginate(50)->render('vendor.pagination.custom_pagination') }}
