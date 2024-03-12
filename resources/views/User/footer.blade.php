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
                <a href="/open-store" style="font-size:1rem">Create new store</a>
                <a href="/canary" style="font-size:1rem">Canary & PGP</a>
                <a href="/faq" style="font-size:1rem">F.A.Q</a>
                @php
                $unreadNews = 0;
                $allNews = \App\Models\News::all();
            
                foreach ($allNews as $news) {
                    $isUnread = true;
            
                    foreach ($user->newsStatuses as $status) {
                        if ($status != null && $news->id == $status->news_id) {
                            $isUnread = false;
                            break;
                        }
                    }
            
                    if ($isUnread) {
                        $unreadNews += 1;
                    }
                }
            @endphp
            
            <style>
              @keyframes blink {
                  0% { opacity: 1; }
                  25% { opacity: 0; }
                  50% { opacity: .5; }
                  75% { opacity:  0; }
                  100% { opacity: 1; }
              }
          
              .blink {
                  animation: blink 2s infinite;
              }
          </style>
          
          <a href="/news" style="color: {{ $unreadNews > 0 ? 'red' : '' }}; font-weight: {{ $unreadNews > 0 ? 'bold' : 'normal' }}" class="{{ $unreadNews > 0 ? 'blink' : '' }}" style="font-size:1rem">News({{ $unreadNews }})</a>

            
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
            <p>Copyright &copy; 2024 Whales Market. All Rights Reserved.</p>
        </div>
    </div>
</div>
