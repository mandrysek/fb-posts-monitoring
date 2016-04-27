<div class="bannedstrings-create">
    <h2>Banned words</h2>

    <p class="note">
        Here you can add words which are banned. Each line for one banned string.
    </p>

    <form method="POST" action="{{ route('bannedStrings.store') }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="form-bannedstrings-create-words">Banned words</label>
            <textarea name="words" id="form-bannedstrings-create-words" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-info">Create</button>
    </form>
</div>