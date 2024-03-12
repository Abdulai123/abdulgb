<div class="container">
    <div class="main-div">
        <div class="notific-container">
            <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Creating Mass Message_</h1>
<p class="notifications-p">This system is powerful mostly used for promos or big deals, users my be get annoyed and remove you from their favorite, report you or even blcoked you!!</p>
            <form action="/store/{{ $store->store_name }}/do/mass/message" method="post" class="support-form"
                style="max-width: 60%; border: 1px solid #ddd; padding: 10px;">
                @if ($errors->any)
                    @foreach ($errors->all() as $error)
                        <p style="color: red; text-align:cenetr;">{{ $error }}</p>
                    @endforeach
                @endif
                @if (session('success'))
                    <p style="text-align: center; color: green;">{{ session('success') }}</p>
                @endif
                @csrf
                <label for="sender" class="subject-label" style="width: fit-content;">Receiver:
                    <select name="receiver" id="">
                        <option value="all">All ({{ $store->StoreFavorited->count() }})</option>
                        @foreach ($store->StoreFavorited as $person)
                            <option value="{{ Crypt::encrypt($person->user->id) }}">{{ $person->user->public_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <input type="hidden" name="message_type" value="message">
                <label for="receiver" class="subject-label" style="width: fit-content;">Subject: <input type="text"
                        name="subject" class="subject" style="border: none; font-size: 1rem;"
                        placeholder="Message Subject..." required> </label>
                <textarea name="contents" placeholder="Write your message here... max 5K characters!" cols="30" rows="10"
                    required></textarea>   
                    <div id="capatcha-code-img">
                        <img src="/user/store/captcha" alt="Captcha Image">
                        <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                            placeholder="Captcha..." required>
                    </div>       
                <input type="submit" class="submit-nxt" value="Send">
            </form>
        </div>
    </div>
</div>
