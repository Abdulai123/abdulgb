<div class="notific-container">
    <header>
        <h1 style="margin: 0px;">Frequently Asked Questions</h1>
    </header>
    <div style="text-align: center; padding:0px; margin:0px;">
        <form action="/senior/staff/{{ $user->public_name }}/new/faq" method="post" style="margin: .3em;">
            @csrf
            @if (session('new_faq'))
                <input type="text" name="question" class="form-input" style="background-color: var(--white-background-color); color: var(--dark-color-text)"
                    placeholder="Write here your question that you gonna answer include ?..."><br><br>
                <textarea name="answer" id="" cols="30" rows="10" style="width: 100%;"
                    placeholder="Write here your answer for your question..."></textarea>
                <button type="submit" class="submit-nxt">Save</button>
            @elseif (session('faq'))
            @php
                $faq = session('faq');
            @endphp
            <input type="text" name="equestion" class="form-input" style="background-color: var(--white-background-color); color: var(--dark-color-text)"
            placeholder="Write here your question that you gonna answer include ?..." value="{{ $faq->question  }}"><br><br>
            <input type="hidden" name="faq" value="{{ encrypt($faq->id) }}">
        <textarea name="eanswer" id="" cols="30" rows="10" style="width: 100%;"
            placeholder="Write here your answer for your question...">{{ $faq->answer }}</textarea>
        <button type="submit" class="submit-nxt">Save Edit</button>
            @else
                <button type="submit" class="input-listing" name="new_faq">Create New F.A.Q</button>
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
          @forelse ($faqs as $faq)
          <details>
            <summary>{{ $faq->question }} </summary>
            <p>{{ $faq->answer }}</p>
            <div style="display: flex; gap:2em;">
                <p>Author: <span class="{{ $faq->user->role }}">{{ $faq->user->role == 'admin' ? 'Admin/' : 'Senior Mod/'}}{{ $faq->user->public_name }}</span></p>
                <form action="/senior/staff/{{ $user->public_name }}/new/faq" method="post">
                    @if ($faq->user_id == $user->id)
                    @csrf
                   
                    <input type="hidden" name="faq" value="{{ encrypt($faq->id) }}">
                    <input type="submit" name="edit" id="" value="Edit" class="input-listing">
                    <input type="submit" name="delete" value="Delete" class="input-listing">
                    @endif
                </form>
            </div>
        </details>
          @empty
              <p>No F.A.Q is found!</p>
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

    /* details[open] > p {
max-height: 0;
overflow: hidden;
transition: max-height 0.3s ease-in-out;
} */

    p {
        color: var(--dark-color-text);
        margin-left: 2em;
        margin-bottom: 1em;
        word-wrap: break-word;

    }
</style>
