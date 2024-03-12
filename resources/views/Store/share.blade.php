<h1>Store Share Access</h1>
<div style="text-align: center; margin: 1em;">
    <form action="" method="post">
        @csrf
        <input type="submit" name="new_share_access" value="Add New Share Access" class="input-listing">
    </form>
</div>

@if (session('success') != null)
    <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
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

<div>
    <p style="color: red; text-align:center;">You do not have required permission to access this feature! <br> You must have recoded sales, <br> You must be active for atlease 3 to 6 months.</p>
</div>
<table>
    <thead>
        <tr>
            <th># ID</th>
            <th>Name</th>
            <th>Total Permissions</th>
            <th>Permissions</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($store->share as $share)
            <tr>
                <td>#{{ $share->id }}</td>
                <td style="text-transform: uppercase;">{{ $share->user->public_name }}</td>
                <td>{{ $share->sharePermission->count() }}/12</td>
                <td>
                    <select name="" id="">
                        @foreach ($share->sharePermission as $permission)
                        <option value="">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="{{ $share->status }}">{{ $share->status }}</td>
                <td><form action="" method="post">
                    @csrf
                    <input type="hidden" name="user" value="{{ Crypt::encrypt($share->user_id) }}" id="">
                    <button type="submit"
                    style="font-size: .8rem; background-color: rgb(0, 75, 128); color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="edit">Edit Permissions</button>
                <button type="submit"
                    style="font-size: .8rem; background-color: darkred; color: #f1f1f1; cursor:pointer; padding: 5px; border: none; border-radius: .5rem;"
                    name="revoke">Revoke</button>
                    </form></td>
            </tr>
            @empty
            <tr>
                <td colspan="5">You don't have any shared access.</td>
            </tr>
            @endforelse

    </tbody>
</table>
@if (session('revoke'))
<div class="alert-box-div">
    <form action="" method="post">
    <div class="alert-box">
        
        <legend>Revoking share access</legend>
        <h3 style="font-size: .8rem;"> Are you sure you want to do this?
            <hr>
        </h3>
        <span>The user will go back to be a normal user account.</span>
       
            @csrf
            <input type="hidden" name="user" value="{{ session('user_id') }}">
            <input type="submit" name="revoke_yes" class="submit-nxt" value="Yes" style="width: max-content;">
        <input type="submit" name="revoke_no" class="submit-nxt" style="background-color: red; width: max-content;" value="No">
    
    </div>
</form>
</div>
@endif

@if (session('edit'))
    @php
        $userShares = session('share');
        $userSharesArray = [];

        // Ensure $userShares is an array before accessing properties
        $userShares = [];

        foreach ($userShares as $share) {
            $userSharesArray[] = ucfirst(str_replace(' ', '_', $share->name));
        }

        echo dd($userShares);
        $allShares = [
            'dashboard', 'add_listing', 'products', 'reviews', 'orders', 'store_stats',
            'affiliate', 'promotion', 'coupons', 'support', 'news', 'rules'
        ];
    @endphp

    <h3>You are editing /</h3>
    <form action="" method="post" class="form-container">
        @csrf

        @foreach ($allShares as $shareName)
            <label for="{{ $shareName }}">
                {{ $loop->iteration }}. {{ ucfirst(str_replace('_', ' ', $shareName)) }}:
                <input type="checkbox" name="{{ $shareName }}" {{ in_array(ucfirst($shareName), $userSharesArray) ? 'checked' : '' }}>
            </label><br><br>
        @endforeach

        <label for="private_name">Enter here the user private name:</label>
        <input type="text" name="private_name" class="form-input" placeholder="Enter here the user private name..." required value="{{ $userShares['private_name'] ?? '' }}">
        <input type="submit" name="create_access" class="submit-nxt" value="Save">
    </form>
@endif

















{{-- add new share access user --}}
@if (session('new_share_access'))
    <p>For you to give a share access to person they must have:</p>
    <ul>
        <li>New User Account</li>
        <li>Has 2FA enable</li>
    </ul>
    <form action="" method="post" class="form-container">
        @csrf
        <label for="">
           1. Dashboard:
            <input type="checkbox" name="dashboard" id="">
        </label><br><br>
        <label for="">
           2. Add Listings:
            <input type="checkbox" name="add_listing" id="">
        </label><br><br>
        <label for="">
           3. Products:
            <input type="checkbox" name="products" id="">
        </label><br><br>
        <label for="">
            4. Reviews:
            <input type="checkbox" name="reviews" id="">
        </label><br><br>
        <label for="">
            5. Orders:
            <input type="checkbox" name="orders" id="">
        </label><br><br>
        <label for="">
            6. Store Stats:
            <input type="checkbox" name="store_stats" id="">
        </label><br><br>
        <label for="">
           7. Affiliate:
            <input type="checkbox" name="affiliate" id="">
        </label><br><br>
        <label for="">
            8. Promotion:
            <input type="checkbox" name="promotion" id="">
        </label><br><br>
        <label for="">
            9. Coupons:
            <input type="checkbox" name="coupons" id="">
        </label><br><br>
        <label for="">
           10. Support:
            <input type="checkbox" name="support" id="">
        </label><br><br>
        <label for="">
           11. News:
            <input type="checkbox" name="news" id="" checked>
        </label><br><br>
        <label for="">
           12. Rules:
            <input type="checkbox" name="rules" id="" checked>
        </label><br><br>
        <input type="text" name="private_name" class="form-input" placeholder="Enter here the user private name..." required>
        <input type="submit" name="create_access" class="submit-nxt" value="Save">
    </form>
@endif
