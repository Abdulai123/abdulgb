<div class="notific-container">
    <header>
        <h1 style="margin: 0px">Market News Blog</h1>
    </header>
    <div style="text-align: center; padding:0px; margin:0px;">
        <form action="/senior/staff/{{ $user->public_name }}/new/news" method="post" style="margin: .3em;">
            @csrf
            @if (session('new_news'))
                <input type="text" name="title" class="form-input"
                    style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                    placeholder="Write your title here..."><br><br>
                <textarea name="contents" id="" cols="30" rows="10" style="width: 100%;"
                    placeholder="Write here your contents..."></textarea>
                <button type="submit" class="submit-nxt">Save</button>
            @elseif(session('news'))
                @php
                    $news = session('news');
                @endphp

                <input type="text" name="etitle" class="form-input"
                    style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                    placeholder="Write your title here..." value="{{ $news->title }}"><br><br>
                <textarea name="contents" id="" cols="30" rows="10" style="width: 100%;"
                    placeholder="Write here your contents...">{{ $news->content }}</textarea>
                    <input type="hidden" name="news" value="{{ encrypt($news->id) }}">
                <button type="submit" class="submit-nxt">Save Edit</button>
            @else
                <button type="submit" class="input-listing" name="new_news">Add News</button>
            @endif
        </form>

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
            @forelse (\App\Models\News::all()->SortByDesc('updated_at') as $news)
                <details>
                    <summary>{{ $news->title }} <span
                            style="font-size: .8rem; font-style:italic; border-bottom: 2px solid #c1c1c1;">{{ $news->created_at->format('l jS \o\f F Y') }}
                            News</span></summary>
                    <p style="white-space: pre-wrap;">{{ $news->content }}</p>
                    <div style="display: flex; gap:2em;">
                        <p>Author: <span
                                class="{{ $news->user->role }}">{{ $news->user->role == 'admin' ? 'Admin/' : 'Senior Mod/' }}{{ $news->user->public_name }}</span>
                        </p>
                        <form action="/senior/staff/{{ $user->public_name }}/new/news" method="post">
                            @if ($news->author_id == $user->id)
                                @csrf

                                <input type="hidden" name="news" value="{{ encrypt($news->id) }}">
                                <input type="submit" name="edit" id="" value="Edit"
                                    class="input-listing">
                            @endif
                        </form>
                    </div>
                </details>
            @empty
                <p>No news is found please add news by clicking the button above!</p>
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
        border: 1px solid grey;
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
