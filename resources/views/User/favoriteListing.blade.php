<div class="main-div">
    <div class="notific-container">
        <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Favorite Listings_</h1>
        <p class="notifications-p">By favoriting a listing you will get notifications about the product (like: restocks, price change, and stocks)!</p>
        @if (session('success'))
        <p style="text-align: center; color: green;">{{ session('success') }}</p>
    @endif
        <table>
            <thead>
                <tr>
                    <th>Listing Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($user->favoriteListings()->paginate(50) as $favoriteListing)
                    <tr>
                        <td><a
                                href="/listing/{{ $favoriteListing->product->created_at->timestamp }}/{{ $favoriteListing->product_id }}">{{ $favoriteListing->product->product_name }}</a>
                        </td>
                        <td>
                            <form action="/favorite/f_listing/{{ $favoriteListing->id }}" method="post">
                                @method('delete')
                                @csrf 
                                <input type="submit" name="submit" style="color: red; border: none; cursor: pointer;"
                                    value="remove">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='2'>No favorite listing found</td>
                    </tr>
                @endforelse


            </tbody>
        </table>
        {{ $user->favoriteListings()->paginate(50)->render('vendor.pagination.custom_pagination') }}

    </div>
</div>
