<div class="main-div" style="margin-top:0px">
    <div class="main-store-div">
        <div class="s-main-image">

            @php
                $avatarKey = $show_user->avatar;
            @endphp
            <img src="data:image/png;base64,{{ !empty($upload_image[$avatarKey]) ? $upload_image[$avatarKey] : $icon['default'] }}"
                class="background-img">
            <div>
                <div class="div-p">
                    <p class="store-name">{{ $show_user->public_name }}<span style="font-size: .5em;">User</span>
                    </p>
                </div>
                <div style="margin-top: 0; display:flex; justify-content:space-around">

                    <span>S I N C E</span> <span>{{ $show_user->created_at->format('d F, Y') }}</span>
                </div>
                <div class="div-p">
                    <p>Status: <span class="{{ $show_user->status }}">{{ $show_user->status }}</span></p> |
                    <p>Disputes: [<span style="color: #28a745;">Won ({{ $show_user->disputes_won }})</span>/<span
                            style="color:#dc3545;">Lost ({{ $show_user->disputes_lost }})</span>]</p>
                </div>
                <div class="div-p">
                    <p>Orders: {{ $show_user->total_orders }}</p> |
                    <p>Spent: ${{ $show_user->spent }}</p> |
                    <p>2FA Enable: {{ $show_user->twofa_enable }}</p>
                </div>
                <div class="div-p">
                    <p style="border-bottom: 3px dotted green">Last Seen: {{ \Carbon\Carbon::parse($show_user->last_seen)->diffForHumans() }}</p>
                    <p class="{{ $show_user->store_status }}">Store Status: {{ $show_user->store_status }}</p>
                </div>
                <div class="div-p">
                    <p>Secret Code: {{ $show_user->pin_code }}</p>
                    <p>Login Phrase: {{ $show_user->login_passphrase }}</p>
                </div>

                <div class="div-p">
                    <p>Unauthorize Access: {{ $show_user->unauthorizes->count() }}</p>
                    <p>Reported Bugs: {{ $show_user->bugs->count() }}</p>
                </div>
                <div class="div-p">
                    <p>Reported Listings: {{ $show_user->reportedListings->count() }}</p>
                    <p>Reported Stores: {{ $show_user->reportedStores->count() }}</p>
                </div>
                <div class="div-p">
                    <p>Favorite Listings: {{ $show_user->favoriteListings->count() }}</p>
                    <p>Favorite Stores: {{ $show_user->favoriteStores->count() }}</p>
                </div>
                <div class="div-p">
                    <p>Store Key: <input type="text" class="form-input" value="{{ $show_user->store_key }}"></p>
                </div>
            </div>
        </div>
        
        <div class="bio">
            @if ($show_user->twofa_enable)
                <h3 style="text-transform: uppercase;">{{ $show_user->public_name }} > PGP KEY</h3>
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $show_user->pgp_key }}</textarea>
            @else
                <h3 style="text-transform: uppercase;">{{ $show_user->public_name }} > PGP KEY</h3>
              
                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">User has not pgp key...</textarea>

            @endif
        </div>
        <form action="" method="post" style="margin-top: 1em;">
            @csrf
            <input type="hidden" name="user_id" value="{{ Crypt::encrypt($show_user->id) }}">

            @if ($show_user->status == 'active')
                <button type="submit"
                    style="font-size: 1rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="ban">Ban {{ $show_user->public_name }}</button>

            @elseif($show_user->status == 'banned')
                <button type="submit"
                    style="font-size: 1rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="un_ban">Un Banned {{ $show_user->public_name }}</button>
            @endif
        </form>
    </div>
</div>

@if ($show_user->wallet == null)
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
                ${{ $show_user->wallet->balance ?? 0.00 }}
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
        <h1>Deposit History</h1>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($show_user->wallet->deposit->sortByDesc('created_at') as $deposit)
                        <tr>
                            <td><input type="text" value="{{ $deposit->txid }}"></td>
                            <td>Confirmed</td>
                            <td>{{ $deposit->amount }} XMR</td>
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

        <h1>Withdraw History</h1>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th>Adress</th>
                        <th>Txid</th>
                        <th>Status</th>
                        <th>XMR</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($show_user->wallet->withdraw->sortByDesc('created_at') as $withdraw)
                        <tr>
                            <td><input type="text" value="{{ $withdraw->address }}"></td>
                            <td><input type="text" value="{{ $withdraw->txid }}"></td>
                            <td>{{ $withdraw->is_confirm == 1 ? "Conform" : "pending" }}</td>
                            <td>{{ $withdraw->amount }}</td>
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