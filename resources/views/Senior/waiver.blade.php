<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            @php
                $avatarKey = $waiver->user->avatar;
            @endphp
            <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $waiver->user->public_name }}<span style="font-size: .5em;">User</span>
                    </p>
                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $waiver->user->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $waiver->user->status }}">{{ $waiver->user->status }}</span></p> |
                    <p>Disputes: [<span style="color: #28a745;">Won ({{ $waiver->user->disputes_won }})</span>/<span
                            style="color:#dc3545;">Lost ({{ $waiver->user->disputes_lost }})</span>]</p>
                </div>
                <div class="div-p">
                    <p>Orders: {{ $waiver->user->total_orders }}</p> |
                    <p>Spent: ${{ $waiver->user->spent }}</p> |
                    <p>2FA Enable: {{ $waiver->user->twofa_enable }}</p>
                </div>
                <div class="div-p">
                    <p>Last Seen: {{ $waiver->user->last_seen  }}</p>
                    <p class="{{ $waiver->user->store_status }}">Store Status: {{ $waiver->user->store_status }}</p>
                </div>
                <div class="div-p">
                    <p>Secret Code: {{ $waiver->user->pin_code }}</p>
                    <p>Login Phrase: {{ $waiver->user->login_passphrase }}</p>
                </div>


                <div class="div-p">
                    <p>Unauthorize Access: {{ $waiver->user->unauthorizes->count() }}</p>
                    <p>Reported Bugs: {{ $waiver->user->bugs->count() }}</p>
                </div>
                <div class="div-p">
                    <p>Reported Listings: {{ $waiver->user->reportedListings->count() }}</p>
                    <p>Reported Stores: {{ $waiver->user->reportedStores->count() }}</p>
                </div>
                <div class="div-p">
                    <p>Favorite Listings: {{ $waiver->user->favoriteListings->count() }}</p>
                    <p>Favorite Stores: {{ $waiver->user->favoriteStores->count() }}</p>
                </div>


                <div class="div-p">
                    <p>Store Key: <input type="text" name="" id="" value="{{ $waiver->user->store_key }}"></p>
                </div>
            </div>
        </div>
        
        <h3 style="text-transform: uppercase;">{{ $waiver->user->public_name }} > Waiver Reason</h3>
        <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $waiver->reason }}</textarea>

        <div class="bio">
            @if ($waiver->user->twofa_enable)
                <h3 style="text-transform: uppercase;">{{ $waiver->user->public_name }} > PGP KEY</h3>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $waiver->user->pgp_key }}</textarea>
            @else
                <h3 style="text-transform: uppercase;">{{ $waiver->user->public_name }} > PGP KEY</h3>
              
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">User has not pgp key...</textarea>

            @endif
        </div>
        <form action="" method="post" style="margin-top: 1em;">
            @csrf
            <input type="hidden" name="user_id" value="{{ Crypt::encrypt($waiver->user->id) }}">
                <button type="submit"
                    style="font-size: 1rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="reject">Reject Waiver</button>
                <button type="submit"
                    style="font-size: 1rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="approve">Approve Waiver</button>
        </form>
    </div>
</div>

@if ($waiver->user->wallet == null)
<h3 style="text-align: center">User Wallet</h3>
<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
    This user has no wallet yet.
</div>
</div>
@else
<h3 style="text-align: center">User Wallet</h3>
<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">

        <div class="ru">
            <span>Wallet Balance</span>
            <p>
                ${{ $waiver->user->wallet->balance ?? 0.00 }}
            </p>
            <style>
                                .ru {
                    justify-content: center;
                    text-align: center;
                    border: 1px solid #443;
                }
            
                .ru span {
                    padding: 10px;
                    color: #445;
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    border-top-left-radius: 5px;
                    border-top-right-radius: 5px;
                }
            
                .ru>span {
                    background-color: skyblue;
                }
            
            
                .ru p{
                    font-weight: bolder;
                    font-size: 2rem;
                }
            </style>
        </div>
        <p style="text-align: center;">Deposit History</p>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Adress</th>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>Amount XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($waiver->user->wallet->deposit as $deposit)
                        <tr>
                            <td>{{ $deposit->address }}</td>
                            <td>{{ $deposit->txid }}</td>
                            <td>{{ $deposit->status }}</td>
                            <td>{{ $deposit->anount }}</td>
                            <td>{{ $deposit->created_at->DiffForHumans() }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan='5'>No deposit history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>



        <p style="text-align: center;">Withdraw History</p>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Adress</th>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>Amount XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($waiver->user->wallet->withdraw as $withdraw)
                        <tr>
                            <td>{{ $withdraw->address }}</td>
                            <td>{{ $withdraw->txid }}</td>
                            <td>{{ $withdraw->status }}</td>
                            <td>{{ $withdraw->anount }}</td>
                            <td>{{ $withdraw->created_at->DiffForHumans() }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan='5'>No withdraw history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
</div>
@endif


@include('Senior.userReviews')