<div class="footer-div">
  <div class="seco-div">
    <div class="left-div" style="display: flex; gap: 50px;">
      <div>
        <p>Active Users: {{ \App\Models\User::where('status', 'active')->where('role', 'user')->count() }}</p>
        <p>Banned Users: {{ \App\Models\User::where('status', 'banned')->where('role', 'user')->count() }}</p>
      </div>
      <div>
        <p>Active Vendors: {{ \App\Models\User::where('status', 'active')->where('role', 'store')->count() }}</p>
        <p>Banned Vendors: {{ \App\Models\User::where('status', 'banned')->where('role', 'store')->count() }}</p>
      </div>
    </div>
    <div class="right-div">
      <div class="canary-news">
        <a href="/whales/admin/{{ $user->public_name }}/show/settings">Settings</a>
        <a href="/whales/admin/{{ $user->public_name }}/show/canary">Canary & PGP KEYs</a>
        <a href="/whales/admin/{{ $user->public_name }}/show/faqs">F.A.Q</a>
      </div>
      <p class="lunched">
        <span>Current Time: {{ now()->setTimezone('UTC')->format('F j, Y H:i:s') }} UTC,</span>
        <span>Launched On: 15th February, 2024</span>
        <span>Server Days: {{ now()->diffInDays(\Carbon\Carbon::parse('2024-02-15')) }}</span>
    </p>
             
    </div>
  </div>
  <div class="bottom-div">
    <div class="cprght">
      <p>Copyright &copy; 2024 Whales Market. All rights reserved.</p>
    </div>
  </div>
</div>
