<style>
    h1 {
        text-align: center;
        color: #445;
        font-size: 2em;
        margin-bottom: 1em;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    p {
        color: #443;
        font-family: Arial, Helvetica, sans-serif;
    }

    .rlink {
        border: 2px solid lightseagreen;
        padding: 5px;
        border-radius: 5px;
        margin: 10px;
        color: #443;
        font-family: Arial, Helvetica, sans-serif;
    }

    .rure {
        display: flex;
        flex-direction: row;
        gap: 20px;
        align-items: center;
        justify-content: center;
        margin: 10px;
    }

    .ru,
    .re {
        justify-content: center;
        text-align: center;
        border: 1px solid #443;
    }

    .ru span,
    .re span {
        padding: 10px;
        color: #445;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .ru>span {
        background-color: skyblue;
    }

    .re>span {
        background-color: lightgreen;
    }

    .ru p,
    .re p {
        font-weight: bolder;
        font-size: 2rem;
    }
</style>
<h1>Referral program</h1>
<p>
    Earn money with us through our referral program!
    Refer users and earn up to <span style="color: green; font-weight: 800;">50%</span> of the escrow profit we make from
    their spendings on this website.

    Find your referral link below:

</p>
<div class="rlink">
    <span>Your referral link is any Whales Market `url/your_public_name` or in the input files for referral ask
        users to add your public name as a referral.
        very easy right so let do it.
    </span>
</div><br>
<div class="rure">
    <div class="ru">
        <span>Referred Users</span>
        <p>
            {{ count($store->referrals) }}
        </p>
    </div>
    <div class="re">
        <span>Referral Earnings</span>
        <p>
            @php
                $totalBalance = 0;
            @endphp

            @foreach ($storeUser->referrals as $referral)
                @php
                    $totalBalance += $referral->balance;
                @endphp
            @endforeach

            ${{ number_format($totalBalance, 2) }}
        </p>
    </div>
</div><br>
<div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Earnings</th>
                <th>Referred at</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($store->referrals()->paginate(100) as $referred)
                <tr>
                    <td>{{ $referred->referredUser->public_name }}</td>
                    <td>{{ $referred->balance }}</td>
                    <td>{{ $referred->created_at->diffForHumans() }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="3">You haven't referred yet any user!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $store->referrals()->paginate(100)->render('vendor.pagination.custom_pagination') }}
</div>
