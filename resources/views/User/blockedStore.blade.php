<div class="main-div">
    <div class="notific-container">
        <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Blocked Stores_</h1>
        <p class="notifications-p">If you block a store you will not be able to see any of the store listings until you
            remove it from you blocked stores!!</p>
        @if (session('success'))
            <p style="text-align: center; color: green;">{{ session('success') }}</p>
        @endif
        <table>
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($user->blockedStores()->paginate(50) as $blockedStore)
                    <tr>
                        <td><a
                                href="/store/{{ $blockedStore->store->store_name }}/{{ $blockedStore->store_id }}/">{{ $blockedStore->store->store_name }}</a>
                        </td>
                        <td>
                            <form action="/blocked/b_store/{{ $blockedStore->id }}" method="post">
                                @method('delete')
                                @csrf
                                <input type="submit" name="submit" style="color: red; border: none; cursor: pointer;"
                                    value="remove">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='3'>No blocked store found</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        {{ $user->blockedStores()->paginate(50)->render('vendor.pagination.custom_pagination') }}

    </div>
</div>
