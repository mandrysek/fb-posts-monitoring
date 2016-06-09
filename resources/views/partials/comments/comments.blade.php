<div class="comments-container">
    <a href="#" class="comments-close"><i class="fa fa-times"></i></a>
    <h2>Comments for {{ $post->state }} <a href="{{ $post->permalink_url }}" target="_blank">post</a></h2>

    <div class="comments">
        @forelse($post->comments as $comment)
            @include('partials.comments.comment')
        @empty
            No comments was found
        @endforelse
    </div>

    <form method="post" action="{{ route('comments.create', $post->id) }}" class="comments-create">
        {!! csrf_field() !!}
        <input type="hidden" name="last_comment" value="{{ $post->comments->last()->id or 0 }}" />
        <div class="form-group">
            <label for="comment-message">Message</label>
            <textarea class="form-control" id="comment-message" name="message" placeholder="Place your comment here"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Comment</button>
    </form>


    <div class="alert alert-danger comments-error"></div>


</div>