<div class="container">
    <div class="main-div">
        <div class="notific-container">
            <h1 class="notifications-h1">Viewing > #OWM_{{ $order->created_at->timestamp }}</h1>
            <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Please scrow down for
                (dispute, feedback and note from store)!!!</p>

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
                            You can extend it for another 72 hours (3 days) by clicking the extend order time below.
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
                        You can extend it for another 72 hours (3 days) by clicking the extend order time below.
                    </span>
                </div>
            @endif


            {{-- expiration-message for fe scammers --}}
            @if ($order->product->payment_type == 'FE' && $order->status == 'pending')
                <div class="expiration-message">
                    <strong style="color: #FF3333;">👨‍💻 Finalize Early Scam Notice:</strong>
                    <span>This order will been Finalize Early(Funds will be released before the product
                        been delivered.), upon store accepting this order. In case of any problem create a support
                        ticket explain well make it short or
                        report this store you will get back your money if you win, but first reach out to the store
                        by clicking the "Click here to message the store" above.</span>
                </div>
            @elseif (
                $order->product->payment_type == 'FE' &&
                    ($order->status != 'pending' &&
                        ($order->status != 'completed' && ($order->status != 'delevered' && $order->status != 'sent'))))
                <div class="expiration-message">
                    <strong style="color: #FF3333;">👨‍💻 Finalize Early Scam Notice:</strong>
                    <span>This order has been Finalize Early(Funds has been released before the product
                        been delivered.) in case of any problem create a support ticket explain well make it short
                        or
                        report this store you will get back your money if you win, but first reach out to the store
                        by clicking the "Click here to message the store" above.</span>
                </div>
                {{-- @endif --}}
            @endif

            <table>
                <tbody>
                    <tr>
                        <th>Item</th>
                        <td>
                            <a
                                href="/whales/admin/show/product/{{ $order->product->created_at->timestamp }}/{{ $order->product_id }}">{{ $order->product->product_name }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Store Name</th>
                        <td>{{ $order->store->store_name }}</td>
                    </tr>
                    <tr>
                        <th>Buyer Name</th>
                        <td> {{ $order->user->public_name }}</td>
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
                            <form action="" method="post">
                                @csrf
                                @if ($order->product->payment_type == 'FE' && $order->status != 'pending')
                                    This order has been accepted by the store and it is Finalize Early(Funds has
                                    been released before the product
                                    been delivered.).
                                @elseif ($order->product->payment_type == 'FE' && $order->status == 'pending')
                                    This order will been Finalize Early(Funds will be released before the product
                                    been delivered.), upon store accepting this order.
                                @else
                                    @switch($order->status)
                                        @case('pending')
                                            <input type="submit" name="cancel" class="cancel" value="Cancel this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="extend_time" value="Extend order time"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold; color: red;">
                                        @break

                                        @case('processing')
                                            <input type="submit" name="dispute" class="dispute" value="Dispute this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="release" class="release"
                                                value="Release funds for this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                        @break

                                        @case('shipped')
                                            <input type="submit" name="dispute" class="dispute" value="Dispute this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="release" class="release"
                                                value="release funds for this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                        @break

                                        @case('delivered')
                                            <input type="submit" name="dispute" class="dispute" value="Dispute this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="release" class="release"
                                                value="Release funds for this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="extend_time" value="Extend order time"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold; color:red;">
                                        @break

                                        @case('dispute')
                                            This order has been disputed, please see dispute process below.
                                        @break

                                        @case('sent')
                                            <input type="submit" name="dispute" class="dispute" value="Dispute this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="release" class="release"
                                                value="Release funds for this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="extend_time" value="Extend order time"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold; color:red;">
                                        @break

                                        @case('dispatched')
                                            <input type="submit" name="dispute" class="dispute" value="Dispute this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="release" class="release"
                                                value="Release funds for this order"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold">
                                            <input type="submit" name="extend_time" value="Extend order time"
                                                style="cursor: pointer;  margin:.4em; font-weight:bold; color: red">
                                        @break

                                        @case('completed')
                                            The funds have been released to the store. Thank you for your honest services.
                                            Please leave a review for this order below.
                                        @break

                                        @case('cancelled')
                                            This order has been cancelled, sorry mate!
                                        @break

                                        @default
                                            Something is wrong with this order, please open a support ticket and paste this
                                            message #{{ $order->created_at->timestamp }}.
                                    @endswitch
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

            @if (!empty($order->store_notes))
                <p style="text-align: center; margin-bottom:0px; text-decoration:underline">Store Notes For The
                    User
                </p>
                <textarea class="support-msg" cols="30" rows="5" style="width: 100%; margin: 1em 0px;"
                    {{ $order->status == 'dispute' ? 'disabled' : '' }}>{{ $order->store_notes }}</textarea>
            @endif



            {{-- if this order has been disputed --}}
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
                        <form action="" method="post" class="message-reply-form">
                            @csrf
                            <div style="text-align: center; margin-bottom: 1em;">
                                @if (
                                    $order->dispute->winner == 'none' &&
                                        $order->dispute->refund_initiated == 'Store' &&
                                        $order->dispute->user_refund_reject == 0)
                                    {{-- Accept funds when the store releases your money --}}
                                    <input type="submit" name="accept_partial_amount"
                                        value="Accept {{ $order->dispute->user_partial_percent }}% Store Refund"
                                        class="input-listing"
                                        style="background-color: #2ecc71; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; animation: blink_per 1s infinite;">

                                    @if ($order->dispute->user_refund_reject === 0)
                                        {{-- Decline refund from user --}}
                                        <input type="submit" name="decline" value="Decline" class="input-listing"
                                            style="background-color: red; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                    @endif
                                @endif

                                <style>
                                    @keyframes blink_per {
                                        50% {
                                            background-color: #3498db;
                                            /* Change to a different color at 50% */
                                        }
                                    }
                                </style>
                                @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'none')
                                    {{-- Release funds to store --}}
                                    <input type="submit" name="release_100" value="Release 100% Funds To Store"
                                        class="input-listing"
                                        style="background-color: #3498db; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                                @endif
                                @if ($order->dispute->winner == 'none' && $order->dispute->refund_initiated == 'none')
                                    {{-- Ask for a partial refund --}}
                                    <input type="submit" name="partial_refund" value="Request Partial Refund"
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
                                <form action="" method="post" class="message-reply-form">
                                    <p style="margin: 0px; color:red; margin-bottom:5px; text-align:center;">NOTE:
                                        The total
                                        percentage for you and the store must be equal to 100%!!!</p>
                                    @csrf

                                    <input type="number" name="user_partial_percent"
                                        placeholder="Enter here your partial percentage  (E.G.,, 1 - 100)! "
                                        style="padding:5px; margin-bottom:1em;" min="1" max="100"
                                        required>

                                    <input type="number" name="store_partial_percent"
                                        placeholder="Enter here the store partial percentage  (E.G.,, 1 - 100)! "
                                        style="padding:5px;" min="1" max="100" required>

                                    <input type="submit" class="submit-nxt" value="Send">
                                </form>
                            </div>
                        @endif

                        @if (session('new_message'))
                            <div style="margin-top: 1em;">
                                <form action="" method="post" class="message-reply-form">
                                    @csrf
                                    <textarea name="contents" class="support-msg" placeholder="Write your reply here... max 1K characters!"
                                        cols="30" rows="10" required></textarea>
                                    <input type="hidden" name="message_type" value="dispute">
                                    <div id="capatcha-code-img" style="margin-top: .5em;">
                                        <img src="/user/captcha" alt="none yet" srcset="">
                                        <input type="text" id="captcha" maxlength="8" minlength="8"
                                            name="captcha" placeholder="Captcha..." required>
                                    </div>
                                    <input type="submit" class="submit-nxt" name="dispute_form" value="Send">
                                </form>
                            </div>
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

                            {{-- is there any staff or none --}}
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

                        {{-- check who make the partial refund and display the message --}}
                        @if (
                            ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                $order->dispute->refund_initiated == 'User')
                            <p style="text-align:center; margin: .3em 0px;"> You have started a partial refund,
                                your percentage is
                                <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                and store percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                            </p>
                        @elseif (
                            ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                $order->dispute->refund_initiated == 'Store')
                            <p style="text-align:center; margin: .3em 0px;"> <span class="storem"
                                    style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/Store/{{ $order->store->store_name }}</span>
                                has started a partial refund, your percentage is <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                and store percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                            </p>
                        @elseif (
                            ($order->dispute->status == 'Partial Refund' || $order->dispute->status == 'closed') &&
                                $order->dispute->refund_initiated == 'staff')
                            <p style="text-align:center; margin: .3em 0px;"> <span
                                    class="{{ $order->dispute->moderator->role }}"
                                    style="font-style: italic; border-bottom:#2ecc71 2px dashed;">/Staff/{{ $order->dispute->moderator->public_name }}</span>
                                has started a partial refund, your percentage is <span
                                    style="{{ $order->dispute->user_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->user_partial_percent }}%{{ $order->dispute->user_partial_percent < 50 ? '📈' : '💹' }}`</span>
                                and store percentage is <span
                                    style="{{ $order->dispute->store_partial_percent < 50 ? 'color: red;' : 'color: green;' }}">`{{ $order->dispute->store_partial_percent }}%{{ $order->dispute->store_partial_percent < 50 ? '📈' : '💹' }}`</span>.
                            </p>
                        @endif




                        {{-- of he decline the offer is there any staff or none --}}
                        @if ($order->dispute->refund_initiated && $order->dispute->status != 'closed')
                            <p style="text-align: center;">
                                @if ($order->dispute->store_refund_reject == 1)
                                    <span style="color:red;"> The store has rejected your partial refund please try
                                        to negiotate with the store and if there is no staff click the request staff
                                        button if presented so that the Staff can share the money between
                                        you and the store as prefered.
                                    </span>
                                @elseif ($order->dispute->user_refund_reject == 1)
                                    <span style="color:red;">You have rejected the store partial refund please try
                                        to negiotate with the store and if there is no staff click the request staff
                                        button if presented so that the Staff can share the money between
                                        you and the store as prefered.</span>
                                @else
                                @endif
                            </p>
                        @endif


                        @if ($order->dispute->status == 'closed')
                            <p style="color: green; text-align: center;">The dispute regarding this order
                                has
                                been resolved, and {{ $order->dispute->winner }} emerged victorious.</p>
                            <p style="color: red; text-align:center;">If you feel that you've been scam by the
                                store please request a staff or open a support ticket.</p>
                        @endif

                        <div class="message-div">

                            @forelse ($order->dispute->conversation->messages->sortByDesc('created_at') as $message)
                                @if ($message->user_id != null)
                                    <div
                                        class="chat-message @if ($message->user->id === $user->id) {{ 'message-right' }} @else {{ 'message-left' }} @endif">
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
                                        <p>{{ $message->content }}</p>
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
            @endif


            @if (($order->product->payment_type == 'FE' && $order->status != 'pending') || $order->status == 'completed')
                @include('User.leaveReview')
            @endif
        </div>
    </div>
</div>
