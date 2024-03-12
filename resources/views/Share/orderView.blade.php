<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $store->store_name }} > Messages</title>
    <link rel="stylesheet" href="{{ @asset('market.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('store.white.css') }}">
    <link rel="stylesheet" href="{{ @asset('filter.css') }}">
</head>

<body>
    @include('Store.navebar')

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
                <p style="font-size: 1em; font-family: Verdana, Geneva, Tahoma, sans-serif; font-style:oblique;">
                    <span style="color: darkorange;">Initiate a Dispute:</span> This option is available if more than 3
                    days have passed since the order was created, and the status is not "pending." The user will click
                    the button to
                    raise concerns or issues. Otherwise, they can simply "cancel" the order.
                </p>

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
                                    href="/listing/{{ $order->product->created_at->timestamp }}/{{ $order->product_id }}">{{ $order->product->product_name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Cost Per Item</th>
                            <td>${{ $order->product->price }}</td>
                        </tr>
                        <tr>
                            <th>Extra Cost</th>
                            <td>${{ $order->extra_id != null ? $order->extraOption->cost : '0.00' }}</td>
                        </tr>
                        <tr>
                            <th>Total Cost </th>
                            <td>${{ number_format($order->product->price * $order->quantity + ($order->extra_id != null ? $order->extraOption->cost : '0.00'), 2) }}
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
                    </tbody>
                </table>
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">User Shipping Address or
                    Extra Notes</p>
                <textarea class="support-msg" cols="20" rows="5" style="width: 100%; margin: 1em 0px;"
                    {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->shipping_address ?? 'User provided no shipping address or extra notes.' }}</textarea>


                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Store Notes For The User
                </p>
                <p style="text-align: center; font-size:.7rem;">Please do not forget to update the order status above
                    after adding your note below or updating it, else the user will not be notify!!!</p>
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
                        <form action="" method="post" class="support-form">
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
                            @if (session('new_message'))
                                <div>
                                    <form
                                        action="/store/{{ $store->store_name }}/do/messages/{{ $order->dispute->conversation->created_at->timestamp }}/{{ $order->dispute->conversation->id }}"
                                        method="post" class="message-reply-form">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ Crypt::encrypt($order->id) }}">
                                        <textarea name="contents" class="support-msg" placeholder="Write your reply here... max 1K characters!"
                                            cols="30" rows="10" required></textarea>
                                        <input type="hidden" name="message_type" value="dispute">
                                        <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                                    </form>
                                </div>
                            @elseif (session('start_partial_refund_user'))
                                Ok let start this partial.
                            @else
                                <form
                                    action="/store/{{ $store->store_name }}/do/messages/{{ $order->dispute->conversation->created_at->timestamp }}/{{ $order->dispute->conversation->id }}"
                                    method="post" class="message-reply-form">
                                    @csrf
                                    <div style="text-align: center; margin-bottom: 1em;">
                                        <input type="hidden" name="message_type" value="dispute">
                                        <input type="hidden" name="order_id"
                                            value="{{ Crypt::encrypt($order->id) }}">

                                        @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'User')
                                            {{-- Accept user fund release --}}
                                            <input type="submit" name="accept_user_fund"
                                                value="Accept  {{ $order->dispute->user_partial_percent }}%  User Fund Released"
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
                                            <input type="submit" name="release_user_fund"
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
                                        {{-- Add new reply for the dispute --}}
                                        <input type="submit" name="new_message" value="New Reply"
                                            class="input-listing">
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
                                            action="/store/{{ $store->store_name }}/do/messages/{{ $order->dispute->conversation->created_at->timestamp }}/{{ $order->dispute->conversation->id }}"
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

                                            <input type="submit" class="submit-nxt" value="Send">
                                        </form>
                                    </div>
                                @endif

                            @endif
                            <p style="text-transform:capitalize; text-align:center; margin: .3em 0px;"
                                class="{{ $order->dispute->status == 'closed' ? 'closed' : 'pending' }}">Dispute
                                Status:
                                {{ $order->dispute->status }}</p>
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
                            @if ($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'Store')
                                <p style="text-align:center; margin: .3em 0px;"> You have started a partial refund,
                                    your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @elseif($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'User')
                                <p style="text-align:center; margin: .3em 0px;"> <span class="user"
                                        style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/User/{{ $order->user->public_name }}</span>
                                    has started a partial refund, your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @elseif($order->dispute->status == 'Partial Refund' && $order->dispute->refund_initiated == 'staff')
                                <p style="text-align:center; margin: .3em 0px;"> <span
                                        class="{{ $order->dispute->moderator->role }}"
                                        style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/Staff/{{ $order->dispute->moderator->public_name }}</span>
                                    has started a partial refund, your percentage is <span
                                        style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                    and the user percentage is <span
                                        style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                                </p>
                            @endif
                            <div class="message-div">
                                @if ($order->dispute->status !== 'closed')
                                    @foreach ($order->dispute->conversation->messages->sortByDesc('created_at') as $message)
                                        @if ($message->user_id != null)
                                            <div
                                                class="chat-message @if ($message->user->id === $storeUser->id) {{ 'message-right' }} @else {{ 'message-left' }} @endif">
                                                <p>{{ $message->content }}</p>
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
                                                <p>{{ $message->content }}</p>
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
                                @else
                                    <p style="color: green; text-align: center;">The dispute regarding this order has
                                        been resolved, and {{ $order->dispute->winner }} emerged victorious.</p>
                                @endif
                            </div>
                        @endif
                    @endif
                @endif


                @if ($order->status == 'completed')
                    @include('User.leaveReview')
                @endif
            </div>
        </div>
    </div>


    @include('Store.footer')
</body>

</html>
