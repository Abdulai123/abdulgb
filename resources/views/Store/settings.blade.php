<legend style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 2rem; text-align: center;">Store Settings
    (Edit)</legend>

@if (session('success'))
    <p style="color:green; text-align:center;">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p style="color: red; text-align:center;">{{ session('error') }}</p>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color: red; text-align:center;">{{ $error }}</p>
    @endforeach
@endif
<div>
        <form action="/store/{{ $store->store_name }}/update/settings" method="post" class="form-container"
            enctype="multipart/form-data">
            @csrf
            <label for="store_description" class="form-label">Store Description (Bio):</label>
            <textarea id="store_description" class="form-textarea" name="store_description" required placeholder="Store bio...">{{ $store->store_description }}</textarea>

            <label for="store_description" class="form-label">Selling:</label>
            <textarea id="store_pgp" name="selling" class="form-textarea" required placeholder="Selling">{{ $store->selling }}</textarea>

            <label for="ship_from" class="form-label">Ship To:
                <select name="ship_to" id="" required>
                    <option value="{{ $store->ship_from }}">{{ $store->ship_from }}</option>
                    @include('User.countries')
                </select>
            </label>

            <label for="ship_to" class="form-label">Ship From:
                <select name="ship_from" id="" required>
                    <option value="{{ $store->ship_to }}">{{ $store->ship_to }}</option>
                    @include('User.countries')
                </select>
            </label>

            <label for="store_description" class="form-label">Store Status:</label>
            <select name="status" class="form-select" id="">
                <option value="{{ $store->status }}" style="text-transform: capitalize;">{{ $store->status }}</option>
                <option value="{{ $store->status == 'active' ? 'vacation' : 'active' }}">
                    {{ $store->status == 'active' ? 'Vacation' : 'Activate' }}</option>
            </select>
            <input type="hidden" name="store_id" value="{{ Crypt::encrypt($store->id) }}">
            <label for="image_path3" class="form-label">Store Avater:</label>
            <input type="file" id="avater" class="form-input" name="avater"
                accept="image/png, image/jpeg, image/jpg"><br>

                <input type="number" id="security-code" class="form-input" name="security_code"
                placeholder="Enter security code" required>
                <div id="capatcha-code-img">
                    <img src="/user/store/captcha" alt="Captcha Image">
                    <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                        placeholder="Captcha..." required>
                </div>
            <div style="display: flex; justify-content:space-between;">
                <input type="submit" class="submit-nxt" name="save_profile" value="Save Changes">
                <span>Page 1/1</span>
            </div>
        </form>
</div>
