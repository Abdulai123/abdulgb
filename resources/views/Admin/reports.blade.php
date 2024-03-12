<h1>Stores and Products Reports ({{ $reports->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>Sort By</th>
            <th>Number Of Rows</th>
            <th>Status</th>
            <th>Type</th>
            <th>D0</th>
        </tr>
    </thead>
    <tbody>
        <form action="/whales/admin/{{ $user->public_name }}/show/reports/search" method="get" style="text-align: center">
            <tr>
                <td>
                    <select name="sort_by" id="sort_by">
                        <option value="newest" {{ old('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="highest_reported" {{ old('sort_by') == 'highest_reported' ? 'selected' : '' }}>Highest Reported</option>
                        <option value="highest_reporter" {{ old('sort_by') == 'highest_reporter' ? 'selected' : '' }}>Highest Reporter</option>
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
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="fake" {{ old('status') == 'fake' ? 'selected' : '' }}>Fake</option>
                    </select>
                </td>
                <td>
                    <select name="type" id="">
                        <option value="all" {{ old('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="store" {{ old('status') == 'store' ? 'selected' : '' }}>Store</option>
                        <option value="listing" {{ old('status') == 'listing' ? 'selected' : '' }}>Listing</option>
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
            <th>Reporter Name</th>
            <th>Reported Name</th>
            <th>Reported Type</th>
            <th>Content</th>
            {{-- <th>Status</th> --}}
            <th>Reported At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $reports = session('reports') ?? $reports;
        @endphp
        @forelse ($reports as $report)
            <tr>
                <td>#{{ $report->id }}</td>
                <td>{{ $report->user->public_name }}</td>

                @if ($report->is_store == 1)
                    <td>{{ $report->store->store_name }}</td>
                    <td>Store</td>
                @else
                    <td>{{ $report->product->product_name }}</td>
                    <td>Product</td>
                @endif
                <td>{{ $report->report }}</td>
                {{-- <td>{{ $report->status }}</td> --}}
                <td>{{ $report->created_at->DiffForHumans() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No report found...</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $reports->render('vendor.pagination.custom_pagination') }}
