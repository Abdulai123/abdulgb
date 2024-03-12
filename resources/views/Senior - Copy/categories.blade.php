<p style="text-align: center">Market Categories ({{ $categories->count() }})</p>
<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Products In</th>
            <th>Status</th>
            <th>Action</th>
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
                <td>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ Crypt::encrypt($category->id) }}">

                        @if ($category->status == 'active')
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="ban">De Activate</button>
                        @elseif($category->status == 'inactive')
                            <button type="submit"
                                style="font-size: .7rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="un_ban">Activate</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
