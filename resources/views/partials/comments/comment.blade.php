<article class="comment media">
    <div class="media-left">
        <figure>
            <img src="https://graph.facebook.com/{{ $comment->user->fb_id }}/picture?size=small">
        </figure>
    </div>

    <div class="media-right">
        <h3>{{ $comment->user->name }}</h3>
        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        <p>
            {{ $comment->message }}
        </p>
    </div>
</article>