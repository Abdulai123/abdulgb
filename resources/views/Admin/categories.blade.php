<h1>Market Categories ({{ $categories->count() }})</h1>
<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Products In</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>#{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent_category_id != null ? ' -> '.$category->category : 'NULL' }}</td>

                @if ($category->parent_category_id == null)
                    <td>{{ $products->where('parent_category_id', $category->id)->count() }}</td>
                @else
                    <td>{{ $products->where('sub_category_id', $category->id)->count() }}</td>
                @endif

                <td class="{{ $category->status }}">{{ $category->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
