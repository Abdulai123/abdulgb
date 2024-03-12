<h1 class="notifications-h1" style="margin:0; padding:0px;;">_Creating New Message For User_</h1>
<form action="" method="post" class="support-form" style="max-width: 60%; border: 1px solid #ddd; padding: 10px;">
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="color: red; text-align:cenetr;">{{ $error }}</p>
        @endforeach
    @endif
    @if (session('success'))
        <p style="text-align: center; color: green;">{{ session('success') }}</p>
    @endif
    @csrf
    <label for="sender" class="subject-label" style="width: fit-content;">Receiver: <input type="text" name=""
            id="" class="subject" style="border: none; font-size: 1rem;"
            value="{{ $order->user->public_name }}" style="cursor: text" disabled></label>
    <input type="hidden" name="message_type" value="message">
    <input type="hidden" name="receiver" value="{{ Crypt::encrypt($order->user->id) }}">
    <label for="receiver" class="subject-label" style="width: fit-content;">Subject: <input type="text"
            name="subject" class="subject" style="border: none; font-size: 1rem;" placeholder="Message Subject..."
            required> </label>
    <textarea name="contents" placeholder="Write your message here... max 5K characters!" cols="30" rows="10"
        required></textarea>

        <div id="capatcha-code-img">
            <img src="/user/store/captcha" alt="Captcha Image">
            <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                placeholder="Captcha..." required>
        </div>
        
    <input type="submit" class="submit-nxt" value="Send">
</form>
