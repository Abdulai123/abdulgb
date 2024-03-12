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
                    <td><img src="data:image/png;base64,{{ $icon['orders'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; color:inherit;">Solds</td>
                    <td>{{ $store->width_sales }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['inventory'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Products</td>
                    <td>{{ $store->products->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['reviews'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reviews</td>
                    <td>{{ $store->reviews->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Won disputes</td>
                    <td>{{ $store->disputes_won }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['dispute'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Lost disputes</td>
                    <td>{{ $store->disputes_lost }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['warn'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Store Reports</td>
                    <td>{{ $store->storeReports->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['thumbs-down-'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Blocked By</td>
                    <td>{{ $store->Storeblocked->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['love'] }}" class="icon-filter" width="30"></td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Favorited By</td>
                    <td>{{ $store->StoreFavorited->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['web-coding'] }}" class="icon-filter" width="30">
                    </td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Reported Bugs</td>
                    <td>{{ $store->user->bugs->count() }}</td>
                </tr>
                <tr>
                    <td><img src="data:image/png;base64,{{ $icon['unauthorized'] }}" class="icon-filter" width="30">
                    </td>
                    <td style="font-weight: bold; font-size:1.2rem; color:inherit;">Unauthorize Access</td>
                    <td>{{ $store->user->unauthorizes->count() }}</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
