<div class="footer-div">
  <div class="seco-div">
    <div class="left-div" style="display: flex; gap: 50px;">
      <div>
        <p>Active Users: 0</p>
        <p>Banned Users: 0</p>
        <p>Active Stores: 0</p>
        <p>Banned Stores: 0</p>
      </div>
      <div>
        <p>Admin: <a href="" style="font-size:12px;" rel="noopener noreferrer">OSINT</a></p>
        <p>Senior Mods:</p>
        <p>Junior Mods:</p>
      </div>
    </div>
    <div class="right-div">
      <div class="canary-news">
        <a href="/senior/staff/{{ $user->public_name }}/show/settings">Settings(Edit)</a>
        <a href="/canary">Canary & pgp keys</a>
        <a href="/faq">F.A.Q</a>
      </div>
      <p class="lunched"><?php
                          $current_time = gmdate('F j, Y H:i:s');
                          echo "<span>Current Time: " . $current_time . " UTC,"  . "</span>";
                          echo "<span>Lunched On: 3rd June, 2023</span>";
                          ?>
      </p>

    </div>
  </div>
  <div class="bottom-div">
    <div class="cprght">
      <p>Copyright &copy; 2023 Whales Market. All rights reserved.</p>
    </div>
  </div>
</div>