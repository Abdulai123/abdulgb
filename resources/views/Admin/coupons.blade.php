<h1 style="text-align: center;">Coupons({{ $coupons->count() }})</h1>

<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/coupons/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="usage_highest" {{ old('sort_by') == 'usage_highest' ? 'selected' : '' }}>Usage Highest</option>
                        <option value="used_highest" {{ old('sort_by') == 'used_highest' ? 'selected' : '' }}>Used Highest</option>
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
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
            <th>Store</th>
            <th>Product</th>
            <th>Coupon Code</th>
            <th>Type</th>
            <th>Discount</th>
            <th>Expired Date</th>
            <th>Usage Limit</th>
            <th>Time Used</th>
            <th>Status</th>
            <th>Created At</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @php
            $coupons =  session('promos') ?? $coupons;
        @endphp
        @forelse ($coupons as $promo)
            <tr>
                <td>{{ $promo->product->store->store_name }}</td>
                <td>#{{ $promo->product->id }}</td>
                <td>{{ $promo->code }}</td>
                <td>{{ $promo->type }}</td>
                <td>{{ $promo->type == 'fixed' ? '$' . $promo->discount : $promo->discount . '%' }}</td>
                <td>{{ $promo->expiration_date }}</td>
                <td>{{ $promo->usage_limit }}</td>
                <td>{{ $promo->times_used }}</td>
                <td {{ $promo->status == 'expired' ? 'style=color:red;' : 'class=active' }}>{{ $promo->status }}</td>
                <td>{{ $promo->updated_at->DiffForHumans() }}</td>
            </tr>
        @empty
            <td colspan="8">There are no coupons code yet....</td>
        @endforelse
    </tbody>
</table>
{{ $coupons->render('vendor.pagination.custom_pagination') }}