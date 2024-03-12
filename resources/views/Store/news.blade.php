<div class="notific-container">
    <header>
        <h1>Market News Blog</h1>
    </header>
    @if (session('success') != null)
        <p style="text-align: center; background: darkgreen; padding: 5px; border-radius: .5rem; color: #f1f1f1;">
            {{ session('success') }}</p>
    @endif
    @if ($errors->any)
        @foreach ($errors->all() as $error)
            <p style="padding: 10px; margin: 10px; border-radius: .5rem; background-color: #dc3545">
                {{ $error }}
            </p>
        @endforeach
    @endif
</div>
<main>
    <section class="faq-section">
        <section class="faq-section">
            @forelse (\App\Models\News::all()->sortByDesc('updated_at') as $news)
                @php
                    $isRead = $store->user->newsStatuses->contains('news_id', $news->id);
                @endphp

                <details style="border: 2px solid {{ $isRead ? 'green' : 'red' }};">
                    <summary>{{ $news->title }} <span
                            style="font-size: .8rem; font-style: italic; border-bottom: 2px solid #c1c1c1;">{{ $news->created_at->format('l jS \of F Y') }}
                            News</span> {!! $isRead == true
                                ? '<span style="color:green; font-size: .8rem; font-style: italic;">Read</span>'
                                : '<span style="color:red; font-size: .8rem; font-style: italic;">UnRead</span>' !!}</summary>
                    <p style="white-space: pre-wrap;">{{ $news->content }}</p>
                    <p>Author: <span
                            class="{{ $news->user->role }}">{{ $news->user->role == 'admin' ? 'Admin/' : 'Senior Mod/' }}{{ $news->user->public_name }}</span>
                    </p>

                    @if (!$isRead)
                        <p>
                        <form action="/store/{{ $store->store_name }}/do/read/news" method="post" class="mark-as-read-form">
                            @csrf
                            <input type="hidden" name="news" value="{{ Crypt::encrypt($news->id) }}">
                            <button type="submit" class="mark-as-read-button">Mark As Read</button>
                        </form>
                        </p>
                    @endif

                    <style>
                        .mark-as-read-form {
                            display: inline-block;
                            /* Make the form inline */
                        }

                        .mark-as-read-button {
                            background-color: #4CAF50;
                            /* Green background color */
                            color: white;
                            /* White text color */
                            border: 1px solid #4CAF50;
                            /* Green border */
                            padding: 8px 16px;
                            /* Padding inside the button */
                            cursor: pointer;
                            /* Cursor on hover */
                            border-radius: 4px;
                            /* Rounded corners */
                            transform: rotate(-15deg);
                            /* Rotate the button by -15 degrees */
                        }

                        .mark-as-read-button:hover {
                            background-color: #45a049;
                            /* Darker green on hover */
                        }
                    </style>

                </details>
            @empty
                <p>No news found!</p>
            @endforelse
        </section>

</main>
</div>
<style>
    h1 {
        text-align: center;
        color: var(--main-color);
    }

    /* FAQ-specific styles */
    .faq-section {
        padding: 2em;
        color: var(--dark-color-text);
    }

    details {
        margin-bottom: 1.4em;
        border-radius: 8px;
        box-shadow: var(--shadow);
        color: var(--dark-color-text);
        border-radius: .5rem;
    }

    summary {
        padding: 1em;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        color: var(--dark-color-text);
        font-size: 1.2rem;
    }

    summary:hover {
        background-color: var(--white-background-color);
        color: var(--main-color);
    }

    /* Optional accordion-style behavior */
    details[open] summary {
        background-color: var(--white-background-color);
        color: var(--main-color);
    }


    p {
        color: var(--dark-color-text);
        margin-left: 2em;
        margin-bottom: 1em;
        word-wrap: break-word;

    }
</style>


</div>
