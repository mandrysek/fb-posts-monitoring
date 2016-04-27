<div class="pages-add">
    <h2>Add Facebook Page</h2>

    <p class="note">
        Here you can add new pages to monitor.
    </p>

    <form method="POST" action="{{ route('pages.add') }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="form-pages-add-id">Facebook page ID</label>
            <input type="text" id="form-pages-add-id" class="form-control" name="page_id" />
        </div>

        <button type="submit" class="btn btn-info">Add</button>
    </form>
</div>