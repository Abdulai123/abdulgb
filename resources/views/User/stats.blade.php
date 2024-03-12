<div class="main-div">
    <div class="notific-container">
        <h1>Your Statistics</h1>
        <table style="border: 1px solid gray;">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Statistic Name</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['wallet'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; color:inherit;">Money Spent</td>
                    <td>${{ $user->spent }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['order'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; color:inherit;">Orders</td>
                    <td>{{ $user->total_orders }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reviews</td>
                    <td>{{ $user->reviews->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Won disputes</td>
                    <td>{{ $user->disputes_won }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Lost disputes</td>
                    <td>{{ $user->disputes_lost }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['love'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Favorite Stores</td>
                    <td>{{ $user->favoriteStores->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['thumbs-up-fav'] }}" class="icon-filter"
                            width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Favorite Listings</td>
                    <td>{{ $user->favoriteListings->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reported Stores</td>
                    <td>{{ $user->reportedStores->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reported Listings</td>
                    <td>{{ $user->reportedListings->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['web-coding'] }}" class="icon-filter" width="30">
                    </td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reported Bugs</td>
                    <td>{{ $user->bugs->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['unauthorized'] }}" class="icon-filter" width="30">
                    </td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Unauthorize Access</td>
                    <td>{{ $user->unauthorizes->count() }}</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
