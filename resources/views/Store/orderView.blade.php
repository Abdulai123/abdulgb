<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $store->store_name }} > Messages</title>
    @if ($store->user->theme == 'dark')
        <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('market.white.css') }}">

    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
    <meta http-equiv="refresh"
        content="{{ session('session_timer') }};url=/kick/store/{{ $store->user->public_name }}/out">

</head>

<body>
    @include('Store.naveBar')

    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <h1 class="notifications-h1">Viewing > #OWM_{{ $order->created_at->timestamp }}</h1>
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Please scrow down for
                    (dispute, feedback and note from store)!!!</p>
                <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; margin-bottom: 2em;">
                    For any additional information or inquiries, please don't hesitate to reach out to the user
                    directly.
                    <a style="font-size: 1em;"
                        href="/store/message/user/{{ $order->user->public_name }}/{{ $order->created_at->timestamp }}/{{ $order->id }}">
                        Click here to message the user
                    </a>
                </p>
                @if (session('success') != null)
                    <p
                        style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
                        {{ session('success') }}</p>
                @endif
                <div>
                    @if ($errors->any())
                        <ul style="margin: auto; list-style-type: none; padding: 0; text-align: center;">
                            @foreach ($errors->all() as $error)
                                <li style="color: red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                </div>

                {{-- auto release money to vendors after 3 days --}}
                @if ($order->product->payment_type == 'Escrow')
                    @if ($order->status == 'sent' || $order->status == 'delivered' || $order->status == 'dispatched')
                        <div class="auto-release-message">
                            <strong style="color: #0c9300;">⏰ Order Auto Release Money To Store Notice:</strong>

                            @php
                                $cancellationTime = $order->updated_at->addDays(3);
                                $timeDifference = now()->diff($cancellationTime);
                                $remainingDays = $timeDifference->days;
                                $remainingHours = $timeDifference->h + $remainingDays * 24; // Convert days to hours
                                $remainingMinutes = $timeDifference->i;
                            @endphp

                            <span>
                                This order money will be auto-release to the store in {{ $remainingHours }} hours
                                {{ $remainingMinutes }}
                                minutes
                                if the status is still [sent, dispatched(for digital items) or delivered(for physical
                                items)].
                                The buyer can extend it for another 72 hours (3 days) if they need to!
                            </span>
                        </div>
                    @endif
                @endif

                {{-- expiration-message --}}
                @if ($order->status == 'pending' && $order->product->payment_type == 'Escrow')
                    <div class="expiration-message">
                        <strong style="color: #FF3333;">⏰ Order Cancellation Notice:</strong>

                        @php
                            $cancellationTime = $order->updated_at->addDays(3);
                            $timeDifference = now()->diff($cancellationTime);
                            $remainingDays = $timeDifference->days;
                            $remainingHours = $timeDifference->h + $remainingDays * 24; // Convert days to hours
                            $remainingMinutes = $timeDifference->i;
                        @endphp

                        <span>
                            This order will be auto-cancelled in {{ $remainingHours }} hours {{ $remainingMinutes }}
                            minutes
                            if the status is still pending.
                            The buyer can extend it for another 72 hours (3 days) if they need to!.
                        </span>
                    </div>
                @endif


                <table>
                    <tbody>
                        <tr>
                            <th>User</th>
                            <td>
                                {{ $order->user->public_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Item</th>
                            <td>
                                <a
                                    href="/store/{{ $store->store_name }}/show/view/{{ $order->product->created_at->timestamp }}/{{ $order->product_id }}">{{ $order->product->product_name }}</a>
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
                                <form
                                    action="/store/{{ $store->store_name }}/do/order/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                    method="post">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ Crypt::encrypt($order->id) }}">

                                    @if ($order->status == 'pending')
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="accept">Accept</button>
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="cancel">Cancel</button>
                                    @elseif($order->status == 'processing' && $order->product->product_type == 'physical')
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="shipped">Shipped</button>
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="dispute">Dispute</button>
                                    @elseif($order->status == 'processing' && $order->product->product_type == 'digital')
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="sent">Sent</button>
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="dispute">Dispute</button>
                                    @elseif($order->status == 'shipped')
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkgreen; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="delivered">Delivered</button>
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="dispute">Dispute</button>
                                    @elseif($order->status == 'sent' || $order->status == 'delivered' || $order->status == 'dispatched')
                                        <button type="submit"
                                            style="font-size: .9rem; background-color: darkorange; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                                            name="dispute">Dispute</button>
                                    @elseif($order->status == 'dispute')
                                        Order is under dispute.
                                    @elseif($order->status == 'completed')
                                        Order Completed
                                    @elseif($order->status == 'cancelled')
                                        Order Cancelled
                                    @endif
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <th>Buyer PGP KEY</th>
                            <td>
                                <textarea name="" id="" cols="30" rows="10" style="width: 100%;">{{ $order->user->pgp_key ?? 'This buyer do not has a pgp key!' }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">User Shipping Address or
                    Extra Notes</p>
                <textarea class="support-msg" cols="20" rows="5" style="width: 100%; margin: 1em 0px;"
                    {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->shipping_address ?? 'User provided no shipping address or extra notes.' }}</textarea>


                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Store Notes For The User
                </p>
                <p style="text-align: center; font-size:.7rem;">Please do not forget to update the order status above or
                    message the buyer
                    after adding your note below or updating it, else the user will not be notify!</p>
                <form
                    action="/store/{{ $store->store_name }}/order/note/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                    style="text-align: center;" method="post">
                    @csrf
                    <textarea class="support-msg" name="store_note" cols="30" rows="5" style="width: 100%; margin: 1em 0px;"
                        placeholder="Add here any note/text for the user to receive..."
                        {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->store_notes }}</textarea>
                    @if ($order->status != 'dispute')
                        <input type="submit" class="submit-nxt" value="Save & Update Note">
                    @endif
                </form>

                {{-- Dispute infos display here --}}
                @if ($order->status == 'dispute')
                    @if ($order->dispute->conversation->messages->count() <= 0)
                        <p style="text-align: center;">This order is currently in the dispute process. Kindly provide
                            your reason below.</p>
                        <form
                            action="/store/{{ $store->store_name }}/do/dispute/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                            method="post" class="support-form">
                            @csrf
                            <textarea name="contents" class="support-msg" id="dispute" cols="90" rows="10"
                                placeholder="Dispute reason here... max characters 1K" required></textarea>
                            <br><br>
                            <div id="capatcha-code-img">
                                <img src="/user/store/captcha" alt="Captcha Image">
                                <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                    placeholder="Captcha..." required>
                            </div>
                            <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                        </form>
                    @endif


                    @if ($order->status == 'dispute')
                        @if ($order->dispute->conversation->messages->count() > 0)
                            <p style="text-align: center;">This order is currently undergoing a dispute process. Please
                                check its status below or respond to any unread messages.</p>
                            @if (session('new_message'))
                                <div>
                                    <form
                                        action="/store/{{ $store->store_name }}/do/dispute/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                        method="post" class="message-reply-form">
                                        @csrf
                                        <input type="hidden" name="order_id"
                                            value="{{ Crypt::encrypt($order->id) }}">
                                        <textarea name="contents" class="support-msg" placeholder="Write your reply here... max 1K characters!"
                                            cols="30" rows="10" required></textarea>
                                        <input type="hidden" name="message_type" value="dispute">
                                        <div id="capatcha-code-img">
                                            <img src="/user/store/captcha" alt="Captcha Image">
                                            <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                                placeholder="Captcha..." required>
                                        </div>
                                        <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                                    </form>
                                </div>
                            @elseif (session('start_partial_refund_user'))
                                Ok let start this partial.
                            @else
                                <form
                                    action="/store/{{ $store->store_name }}/do/dispute/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                    method="post" class="message-reply-form">
                                    @csrf
                                    <div style="text-align: center; margin-bottom: 1em;">
                                        <input type="hidden" name="message_type" value="dispute">
                                        <input type="hidden" name="order_id"
                                            value="{{ Crypt::encrypt($order->id) }}">

                                        @if (
                                            $order->dispute->winner == 'none' &&
                                                $order->dispute->refund_initiated == 'User' &&
                                                $order->dispute->store_refund_reject == 0)
                                            {{-- Accept user fund release --}}
                                            <input type="submit" name="accept_partial_amount"
                                                value="Accept  {{ $order->dispute->store_partial_percent }}%  User Fund Released"
                                                class="input-listing"
                                                style="background-color: #2ecc71; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; animation: blink 1s infinite;">

                                            @if ($order->dispute->store_refund_reject === 0)
                                                {{-- Decline refund from user --}}
                                                <input type="submit" name="decline" value="Decline"
                                                    class="input-listing"
                                                    style="background-color: red; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                            @endif
                                        @endif

                                        <style>
                                            @keyframes blink {
                                                50% {
                                                    background-color: #3498db;
                                                    /* Change to a different color at 50% */
                                                }
                                            }
                                        </style>
                                        @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'none')
                                            {{-- Release funds to user --}}
                                            <input type="submit" name="release_100"
                                                value="Release 100% Funds To User" class="input-listing"
                                                style="background-color: #3498db; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                        @endif
                                        @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'none')
                                            {{-- Start partial refund for user --}}
                                            <input type="submit" name="partial_refund" value="Start Partial Refund"
                                                class="input-listing"
                                                style="background-color: #e74c3c; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                        @endif


                                        @if ($order->dispute->mediator_request === 0)
                                            {{-- Request staff to join the dispute --}}
                                            <input type="submit" name="request_staff" value="Request Staff"
                                                class="input-listing"
                                                style="background-color: rgb(175, 97, 1); color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                        @endif
                                        @if ($order->dispute->status != 'closed')
                                            {{-- Add new reply for the dispute --}}
                                            <input type="submit" name="new_message" value="New Reply"
                                                class="input-listing">
                                        @endif
                                    </div>
                                </form>

                                @if (session('percentage_error'))
                                    <p
                                        style="font-size: 1em; padding:5px; font-family: Verdana, Geneva, Tahoma, sans-serif; font-style:oblique; background-color:darkred; color:#f1f1f1; border-radius:5px;">
                                        {{ session('percentage_error') }}
                                    </p>
                                @endif
                                {{-- pertial percentage request form --}}
                                @if (session('partial_refund_form'))
                                    <div style="margin-top: 1em;">
                                        <form
                                            action="/store/{{ $store->store_name }}/do/dispute/{{ $order->created_at->timestamp }}/{{ $order->id }}"
                                            method="post" class="message-reply-form">
                                            <p style="margin: 0px; color:red; margin-bottom:5px; text-align:center;">
                                                NOTE: The total percentage for you and the user must be equal to 100%!!!
                                            </p>
                                            @csrf
                                            <input type="hidden" name="order_id"
                                                value="{{ Crypt::encrypt($order->id) }}">
                                            <input type="number" name="store_partial_percent"
                                                placeholder="Enter here your partial percentage  (E.G.,, 1 - 100)! "
                                                style="padding:5px; margin-bottom:1em;" min="1" max="100"
                                                required>

                                            <input type="number" name="user_partial_percent"
                                                placeholder="Enter here the user partial percentage  (E.G.,, 1 - 100)! "
                                                style="padding:5px;" min="1" max="100" required>

                                                <div id="capatcha-code-img">
                                                    <img src="/user/store/captcha" alt="Captcha Image">
                                                    <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                                        placeholder="Captcha..." required>
                                                </div>
                                                
                                            <input type="submit" class="submit-nxt" value="Send">
                                        </form>
                                    </div>
                                @endif

                            @endif
                            <p style="text-transform:capitalize; text-align:center; margin: .3em 0px;"
                                class="{{ $order->dispute->status == 'closed' ? 'closed' : 'pending' }}">Dispute
                                Status:
                                {{ $order->dispute->status }}</p>
                            <p style="text-transform: capitalize; text-align: center; margin: .3em 0px;">User Last
                                Seen: {{ \Carbon\Carbon::parse($order->user->last_seen)->diffForHumans() }}</p>
                            <p style="text-transform: capitalize; text-align: center; margin: .3em 0px;">Store Last
                                Seen: {{ \Carbon\Carbon::parse($order->store->user->last_seen)->diffForHumans() }}</p>
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

                            @if (
                                ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                    $order->dispute->refund_initiated == 'Store')
                                <p style="text-align:center; margin: .3em 0px;"> You have started a partial refund,
                                    your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @elseif(
                                ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                    $order->dispute->refund_initiated == 'User')
                                <p style="text-align:center; margin: .3em 0px;"> <span class="user"
                                        style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/User/{{ $order->user->public_name }}</span>
                                    has started a partial refund, your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @elseif(
                                ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                    $order->dispute->refund_initiated == 'staff')
                                <p style="text-align:center; margin: .3em 0px;"> <span
                                        class="{{ $order->dispute->moderator->role }}"
                                        style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/Staff/{{ $order->dispute->moderator->public_name }}</span>
                                    has started a partial refund, your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @endif


                            {{-- of he decline the offer is there any staff or none --}}
                            @if ($order->dispute->refund_initiated && $order->dispute->status != 'closed')
                                <p style="text-align: center;">
                                    @if ($order->dispute->user_refund_reject == 1)
                                        <span style="color:red;"> The user has rejected your partial refund please try
                                            to negiotate with the user and if there is no staff click the request staff
                                            button if presented so that the Staff can share the money between
                                            you and the store as prefered.
                                        </span>
                                    @elseif ($order->dispute->store_refund_reject == 1)
                                        <span style="color:red;">You have rejected the user partial funds please try
                                            to negiotate with the user and if there is no staff click the request staff
                                            button if presented so that the Staff can share the money between
                                            you and the user as prefered.</span>
                                    @else
                                    @endif
                                </p>
                            @endif

                            @if ($order->dispute->status == 'closed')
                                <p style="color: green; text-align: center;">The dispute regarding this order has
                                    been resolved, and {{ $order->dispute->winner }} emerged victorious.</p>
                                <p style="color: red; text-align:center;">If you feel that you've been scam by the user
                                    please request a staff or open a support ticket.</p>
                            @endif

                            <div class="message-div">

                                @foreach ($order->dispute->conversation->messages->sortByDesc('created_at') as $message)
                                    @if ($message->user_id != null)
                                        <div
                                            class="chat-message @if ($message->user->id === $storeUser->id) {{ 'message-right' }} @else {{ 'message-left' }} @endif">
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
                                                    @if ($status->user_id != $storeUser->id && $status->user_id != $message->user->id)
                                                        <span
                                                            class="{{ $status->is_read == 1 ? 'message-read' : 'message-unread' }}">[{{ $status->user->role == 'store' ? $status->user->store->store_name : $status->user->public_name }}
                                                            {{ $status->is_read == 1 ? 'read' : 'unread' }}],
                                                        </span>
                                                    @elseif ($status->user_id == $storeUser->id && $status->user_id != $message->user->id)
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
                                            <p class="owner"> <span class="senior"
                                                    style="margin-right:1em">/mod/System
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
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>


    @include('Store.footer')
</body>

</html>
