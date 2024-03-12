
    <div class="notific-container">
        <div>
            <h1 style="text-align: center;">Your Private Mirror</h1>
            <div style="border: 2px solid lightseagreen;padding: 5px;border-radius: 5px;margin: 10px; font-weight: 800;">
                <span>Your Private Mirror Link: {{ \App\Models\Mirror::where('type', 'store')->first()->link ??  $host = request()->getHost() }}</span>
            </div><br>
        </div>
    </div>
