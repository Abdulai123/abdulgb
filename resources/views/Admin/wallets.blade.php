<h1 style="text-align: center;  margin:0px; padding:0px;">Wallets({{ $wallets->count() }})</h1>
@php
    $balance = 0;

    foreach ($wallets as $wallet) {
        $balance += $wallet->balance;
    }
@endphp

<h1 style="text-align: center; margin:0px; padding:0px;">Total Balance (${{ $balance }})</h1>

<div style="text-align: center">
    <a href="/whales/admin/{{ $user->public_name }}/show/deposit">Deposit</a> ||
<a href="/whales/admin/{{ $user->public_name }}/show/withdraw">Withdraw</a>
</div>

<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Role</th>
            <th>Status</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/wallets/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="balance_highest" {{ old('sort_by') == 'balance_highest' ? 'selected' : '' }}>Balance Highest</option>
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
                    <select name="role" id="">
                        <option value="all" {{ old('role') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="store" {{ old('role') == 'store' ? 'selected' : '' }}>Store</option>
                        <option value="junior" {{ old('role') == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="senior" {{ old('role') == 'senior' ? 'selected' : '' }}>Senior</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </td>
                <td>
                    <select name="status" id="">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        <option value="vacation" {{ old('status') == 'vacation' ? 'selected' : '' }}>Vacation</option>
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
            <th>Owner</th>
            <th>Owner Role</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $wallets = session('wallets') ?? $wallets;
        @endphp
        
        @foreach ($wallets as $wallet)
        <tr>
            <td>#{{ $wallet->id }}</td>
            <td>{{ $wallet->user->public_name }}</td>
            <td class="{{ $wallet->user->role }}">{{ $wallet->user->role }}</td>
            <td>{{ $wallet->balance}}</td>
            <td class="{{ $wallet->user->status }}">{{ $wallet->user->status }}</td>
            <td>{{ $wallet->created_at->DiffForHumans() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>