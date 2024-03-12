<h1 style="text-align: center;">Escrows({{ $escrows->count() }})</h1>
@if (session('success') != null)
<p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
    {{ session('success') }}</p>
@endif
@if ($errors->any)
@foreach ($errors->all() as $error)
    <p style="padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545">
        {{ $error }}
    </p>
@endforeach
@endif
<form action="" method="post" style="text-align: center; margin-bottom:1em;">
    @csrf
    @if (session('pay'))
    <input type="number" name="amount" placeholder="Amount.... $0.0" id="" class="form-input">
    <label for="sender" class="subject-label" style="width: fit-content;">Receiver:
        <select name="payee" id="">
            @foreach (\App\Models\User::all() as $staff)
                <option value="{{ $staff->id }}" class="{{ $staff->role }}">{{ $staff->public_name }}({{ $staff->role }})</option>
            @endforeach
        </select>
    </label>
    <input type="submit" name="save" id="" class="submit-nxt" value="Pay">
        
    @else
    <input type="submit" name="pay" id="" class="input-listing" value="Pay Out">
        
    @endif
</form>
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
        <form action="/whales/admin/{{ $user->public_name }}/show/escrows/search" method="get"
            style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="amount_highest" {{ old('sort_by') == 'amount_highest' ? 'selected' : '' }}>Amount Highest</option>
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
                        <option value="released" {{ old('status') == 'released' ? 'selected' : '' }}>Released</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
            <th>Amount</th>
            <th>Order</th>
            <th>Payment Type</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $escrows = session('escrows') ?? $escrows;
        @endphp
        @forelse ($escrows as $escrow)
            <tr class="self-container">
                <td>#{{ $escrow->id }}</td>
                <td>${{ $escrow->fiat_amount }}</td>
                <td>#{{ $escrow->order_id }}</td>
                <td class="{{ $escrow->order->product->payment_type ?? 'pending' }}">{{ $escrow->order->product->payment_type ?? 'N/A' }}</td>
                <td class="{{ $escrow->status }}">{{ $escrow->status }}</td>
                <td>{{ $escrow->created_at->DiffForHumans() }}</td>
            </tr>
            </form>
        @empty

            <tr>
                <td colspan="5">
                    <span class="no-notification">
                        Cart is currently empty.
                    </span>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $escrows->render('vendor.pagination.custom_pagination') }}

