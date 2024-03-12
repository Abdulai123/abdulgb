<legend style="text-align: center;">{{ $store->store_name }} > Store Share Access </legend>
<div style="text-align: center; margin: 1em;">
    <form action="" method="post">
        @csrf
        <input type="submit" name="new_share_access" value="Add New Share Access" class="input-listing">
    </form>
</div>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Permissions</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="4">You don't have any shared access.</td>
        </tr>
    </tbody>
</table>
@if (session('new_share_access'))
    hmm let go............
    <form action="" method="post" class="form-container">
        <label for="">
           1. Dashboard:
            <input type="checkbox" name="dashboard" id="">
        </label><br><br>
        <label for="">
           2. Add Listings:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
           3. Products:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
            4. Reviews:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
            5. Orders:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
            6. Store Stats:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
           7. Affiliate:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
           8. Wallet:
            <input type="checkbox"  name="" id="">
        </label><br><br>
        <label for="">
            9. Promotion:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
            10. Coupons:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
           11. Support:
            <input type="checkbox" name="" id="">
        </label><br><br>
        <label for="">
           12. News:
            <input type="checkbox" name="" id="" checked>
        </label><br><br>
        <label for="">
           13. Rules:
            <input type="checkbox" name="" id="" checked>
        </label><br><br>
        <input type="submit" class="submit-nxt" value="Save">
    </form>
@endif
