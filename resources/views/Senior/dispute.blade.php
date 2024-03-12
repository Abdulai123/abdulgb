<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif
    <link rel="stylesheet" href="{{ @asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
<meta http-equiv="refresh" content="{{ session('session_timer') }};url=/senior/staff/kick/{{ $user->public_name }}/out">

    <title>Whales Market | {{ $action != null ? $action : $user->public_name . ' Moderator' }}</title>
</head>

<body>
    @include('Senior.naveBar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <h1 class="notifications-h1">Viewing Dispute > #OWM_{{ $order->created_at->timestamp }}</h1>
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Please scrow down for
                    (dispute replies, feedback and note from store and user)!!!</p>
                <table>
                    <tbody>
                        <tr>
                            <th>Item</th>
                            <td>
                                <a
                                    href="/senior/staff/show/product/{{ $order->product->created_at->timestamp }}/{{ $order->product_id }}">{{ $order->product->product_name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Cost Per Item</th>
                            <td>${{ $order->cost_per_item }}</td>
                        </tr>
                        <tr>
                            <th>Extra Cost</th>
                            <td>+${{ $order->extra_amount }} - {{ $order->extraOption->name }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>-${{ $order->discount ?? 0.0 }}</td>
                        </tr>
                        <tr>
                            <th>Total Cost </th>
                            <td>${{ number_format($order->cost_per_item * $order->quantity - $order->discount + $order->extra_amount, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td>{{ $order->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $order->updated_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <th>Payment</th>
                            <td class="{{ $order->product->payment_type }}">
                                {{ '{' . $order->product->payment_type . '}' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="{{ $order->status }}">{{ $order->status }}</td>
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td>
                                This order has been disputed, please see dispute process below.
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">User Shipping Address or
                    Extra Notes</p>
                <textarea class="support-msg" cols="20" rows="5" style="width: 100%; margin: 1em 0px;"
                    {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->shipping_address ?? 'User provided no shipping address or extra notes.' }}</textarea>

                @if ($order->store_notes != null)
                    <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Store Notes For The
                        User
                    </p>
                    <textarea class="support-msg" cols="30" rows="5" style="width: 100%; margin: 1em 0px;"
                        {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->store_notes }}</textarea>
                @endif



                @if ($order->status == 'dispute')
                    @if ($order->dispute->conversation->messages->count() <= 0)
                        <p style="text-align: center;">This order is currently in the dispute process. Kindly provide
                            your reason below.</p>
                        <form
                            action="/senior/staff/{{ $user->public_name }}/do/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                            method="post" class="support-form">
                            @csrf
                            <textarea name="contents" class="support-msg" id="dispute" cols="90" rows="10"
                                placeholder="Dispute reason here... max characters 1K" required></textarea>
                            <br><br>
                            <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                        </form>
                    @endif



                    @if ($order->status == 'dispute')
                        @if ($order->dispute->conversation->messages->count() > 0)
                            <p style="text-align: center;">This order is currently undergoing a dispute process. Please
                                check its status below or respond to any unread messages.</p>
                            <form
                                action="/senior/staff/{{ $user->public_name }}/do/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                                method="post" class="message-reply-form">
                                @csrf
                                <div style="text-align: center; margin-bottom: 1em;">
                                    @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'none')
                                        {{-- Release funds to store --}}
                                        <input type="submit" name="release_100_store"
                                            value="Release 100% Fund To Store" class="input-listing"
                                            style="background-color: #3498db; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">

                                        {{-- Release funds to user --}}
                                        <input type="submit" name="release_100_user" value="Release 100% Funds To User"
                                            class="input-listing"
                                            style="background-color: #027400; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                    @endif
                                    @if (
                                        $order->dispute->winner == 'none' &&
                                            $order->dispute->refund_accept == 'none' &&
                                            $order->dispute->refund_initiated == 'none')
                                        {{-- Ask for a partial refund --}}
                                        <input type="submit" name="partial_refund" value="Start Partial Refund"
                                            class="input-listing"
                                            style="background-color: #e74c3c; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                    @endif

                                    {{-- .. let mode reply or join dispite --}}
                                    @if ($order->dispute->status != 'closed')

                                    @if ($dispute->mediator_id == null && $dispute->mediator_request == 1)
                                    <button type="submit"
                                    style="font-size: 1.1rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                    name="join_dispute">Join</button>

                                    @elseif ($dispute->mediator_id == $user->id)
                                    {{-- Add new reply for the dispute --}}
                                    <input type="submit" name="new_message" value="New Reply"
                                class="input-listing">
                                @elseif ($dispute->mediator_id == null && $dispute->mediator_request == 0)
                                <button type="submit"
                                style="font-size: 1.1rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                name="join_dispute">Enforce Join</button>
                                @else
                                Other staff is here already.
                                    @endif
                                    @endif

                                </div>
                            </form>
                        @endif



                        {{-- errors --}}
                        @if (session('percentage_error'))
                            <p
                                style="font-size: 1em; padding:5px; font-family: Verdana, Geneva, Tahoma, sans-serif; font-style:oblique; background-color:darkred; color:#f1f1f1; border-radius:5px;">
                                {{ session('percentage_error') }}
                            </p>
                        @endif


                        {{-- pertial percentage request form --}}
                        @if (
                            $order->dispute->status == 'Partial Refund' &&
                                ($order->dispute->user_refund_reject == 1 || $order->dispute->store_refund_reject == 1))
                            <div style="margin-top: 1em;">
                                <form
                                    action="/senior/staff/{{ $user->public_name }}/do/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                                    method="post" class="message-reply-form">
                                    <p style="margin: 0px; color:red; margin-bottom:5px; text-align:center;">
                                        @if ($order->dispute->user_refund_reject == 1 || $order->dispute->store_refund_rejec == 1)
                                            The {{ $order->dispute->user_refund_reject == 1 ? 'user' : 'store' }} has
                                            rejected the
                                            {{ $order->dispute->user_refund_reject == 1 ? 'store' : 'user' }} partial
                                            refund, please try see how best they
                                            can work it out, and edit the percentages below.
                                        @endif The total
                                        percentage for the user and the store must be equal to 100%!!!
                                    </p>
                                    @csrf

                                    <label for="">Store Percentage:
                                        <input type="number" name="store_partial_percent"
                                            placeholder="Enter here the user partial percentage  (E.G.,, 1 - 100)! "
                                            style="padding:5px; margin-bottom:1em;" min="1" max="100"
                                            value="{{ $order->dispute->store_partial_percent }}" required>
                                    </label>

                                    <label for="">
                                        User Percentage:
                                        <input type="number" name="user_partial_percent"
                                            placeholder="Enter here the store partial percentage  (E.G.,, 1 - 100)! "
                                            style="padding:5px;" min="1" max="100"
                                            value="{{ $order->dispute->user_partial_percent }}" required> </label>

                                    <input type="submit" class="submit-nxt" name="finalize" value="Finalize">
                                </form>
                            </div>
                        @endif

                        @if (session('new_message'))
                            <div style="margin-top: 1em;">
                                <form
                                    action="/senior/staff/{{ $user->public_name }}/do/dispute/{{ $dispute->created_at->timestamp }}/{{ $dispute->id }}"
                                    method="post" class="message-reply-form">
                                    @csrf
                                    <textarea name="contents" class="support-msg" placeholder="Write your reply here... max 1K characters!"
                                        cols="30" rows="10" required></textarea>
                                    <input type="hidden" name="message_type" value="dispute">
                                    <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                                </form>
                            </div>
                        @endif

                        {{-- dispute status status --}}
                        <p style="text-transform:capitalize; text-align:center; margin: .3em 0px;"
                            class="{{ $order->dispute->status == 'closed' ? 'closed' : 'pending' }}">Dispute
                            Status:
                            {{ $order->dispute->status }}</p>

                        {{-- user and store status --}}
                        <p style="text-transform: capitalize; text-align: center; margin: .3em 0px;">User Last Seen:
                            {{ \Carbon\Carbon::parse($order->user->last_seen)->diffForHumans() }}</p>
                        <p style="text-transform: capitalize; text-align: center; margin: .3em 0px;">Store Last Seen:
                            {{ \Carbon\Carbon::parse($order->store->user->last_seen)->diffForHumans() }}</p>

                        {{-- has moderator been joined --}}
                        <p style="text-align: center; font-weight:800;">
                            @if ($order->dispute->mediator_request === 1)
                                @if ($order->dispute->mediator_id != null)
                                    Staff:
                                    <span
                                        class="{{ $order->dispute->moderator->role }}">/{{ $order->dispute->moderator->role != 'Admin' ? 'Mod' : 'Admin' }}/{{ $order->dispute->moderator->public_name }}</span>
                                @else
                                    Staff: <span style="color:red;">No staff has join yet...</span>
                                @endif
                            @endif
                        </p>


                        {{-- what is the partial percentages and who started it --}}
                        @if ($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'Store')
                            <p style="text-align:center; margin: .3em 0px;"> The store has started a partial refund,
                                his/her percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                and the user percentage is <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                            </p>
                        @elseif($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'User')
                            <p style="text-align:center; margin: .3em 0px;"> The user
                                has started a partial refund, his/her percentage is
                                <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                and the store percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                            </p>
                        @elseif($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'staff')
                            <p style="text-align:center; margin: .3em 0px;"> <span
                                    class="{{ $order->dispute->moderator->role }}"
                                    style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/Staff/{{ $order->dispute->moderator->public_name }}</span>
                                has started a partial refund, The store percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                and the user percentage is <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                            </p>
                        @endif

                        @if ($order->dispute->status == 'closed')
                        <p style="color: green; text-align: center;">The dispute regarding this order
                            has
                            been resolved, and {{ $order->dispute->winner }} emerged victorious.</p>
                    @endif

                        <div class="message-div">
                            @forelse ($order->dispute->conversation->messages->sortByDesc('created_at') as $message)
                                @if ($message->user_id != null)
                                    <div
                                        class="chat-message @if ($message->user->id === $user->id) {{ 'message-right' }} @else {{ 'message-left' }} @endif">
                                        <p style="white-space: pre-line; overflow-wrap: break-word; text-align:left;">{{ $message->content }}</p>
                                        <p class="owner "> <span
                                                class="{{ $message->user->role == 'store' ? 'storem' : $message->user->role }}"
                                                style="margin-right:1em">
                                                /@if ($message->user->role == 'junior' || $message->user->role == 'senior')
                                                    {{ $message->user->role . ' mod' }}
                                                @else
                                                    {{ $message->user->role }}
                                                @endif/{{ $message->user->public_name }}
                                            </span>

                                            @foreach ($message->status as $status)
                                                @if ($status->user_id != $user->id && $status->user_id != $message->user->id)
                                                    <span
                                                        class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                                        {{ $status->is_read == 1 ? 'read' : 'unread' }}],
                                                    </span>
                                                @elseif ($status->user_id == $user->id && $status->user_id != $message->user->id)
                                                    <span
                                                        class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                                        {{ $status->is_read == 1 ? 'read' : 'unread' }}],
                                                    </span>
                                                @endif
                                            @endforeach
                                            sent {{ $message->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @else
                                    <div class="chat-message message-left">
                                        <p style="white-space: pre-line; overflow-wrap: break-word; text-align:left;">{{ $message->content }}</p>
                                        <p class="owner"> <span class="senior" style="margin-right:1em">/mod/System
                                                Mod</span>
                                            @foreach ($message->status as $status)
                                                <span
                                                    class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                                    {{ $status->is_read == 1 ? 'read' : 'unread' }}], </span>
                                            @endforeach
                                            sent {{ $message->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @endif
                            @empty
                                No message found for this dispute.
                            @endforelse
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @include('Senior.footer')
</body>

</html>
