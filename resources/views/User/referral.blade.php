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
        font-weight: 800;
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

<div class="main-div">
    <div class="notific-container">
        <h1>Referral program</h1>
        <p>
            Earn money with us through our referral program!
            Refer users and earn up to <span style="color: green; font-weight: 800;">50%</span> of the escrow profit we
            make from
            their spendings on this website.

            Find your referral link below:

        </p>
        <div class="rlink">
            <span>Your Referral code is your public name: <input type="text" name="" id="" value="{{ $user->public_name }}"></span>
        </div><br>
        <div class="rure">
            <div class="ru">
                <span>Referred Users</span>
                <p>
                    {{ $user->referrals->count() }}
                </p>
            </div>
            <div class="re">
                <span>Referral Earnings</span>
                <p>
                    @php
                        $totalBalance = 0;
                    @endphp

                    @foreach ($user->referrals as $referral)
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
                <tbody>
                    @forelse ($user->referrals()->paginate(50) as $referral)
                        <tr>

                            <td>{{ $referral->referredUser->public_name }}</td>
                            <td>${{ $referral->balance }}</td>
                            <td>{{ $referral->created_at->DiffForHumans() }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">You haven't referred yet any user!</td>
                        </tr>
                    @endforelse
                </tbody>
                </tbody>
            </table>
            {{ $user->referrals()->paginate(50)->render('vendor.pagination.custom_pagination') }}
        </div>
    </div>
</div>
