<div class="main-div">
    <div class="notific-container">
        <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Favorite Stores_</h1>
        <p class="notifications-p">By favoriting a store, The store can send you a message about anything.!!</p>
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
                @forelse ($user->favoriteStores()->paginate(50) as $favoriteStore)
                    <tr>
                        <td><a href="/store/{{ $favoriteStore->store->store_name }}/{{ $favoriteStore->store_id }}">{{ $favoriteStore->store->store_name }}</a>
                        </td>

                        <td>
                            <form action="/favorite/f_store/{{  $favoriteStore->id }}" method="post">
                                @method('delete')
                                @csrf 
                                <input type="submit" name="submit"
                                    style="color: red; border: none; cursor: pointer;" value="remove">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='3'>No favorite store found</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        {{ $user->favoriteStores()->paginate(50)->render('vendor.pagination.custom_pagination') }}

    </div>
</div>
