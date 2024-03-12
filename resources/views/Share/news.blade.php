@if ($news->count() > 0)
    @foreach ($news->sortByDesc('updated_at') as $latest_news)
        <div class="news-div">
            <h2 class="news-title">
                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter" alt="News Icon" width="25">
                {{ $latest_news->title }}
                <img src="data:image/png;base64,{{ $icon['news'] }}" class="icon-filter" alt="News Icon" width="25">
            </h2>
            <div class="news-content">
                <p>{{ $latest_news->content }}</p>
            </div>
            <div class="news-meta">
                <span class="author {{ $latest_news->user->role }}">
                    /{{ $latest_news->user->role }}/{{ $latest_news->user->public_name }}
                </span>
                <span class="date">{{ $latest_news->created_at->diffForHumans() }}</span>
            </div>
        </div>
    @endforeach
@else
    No news yet please check back later....
@endif
