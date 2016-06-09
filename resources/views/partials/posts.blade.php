<nav class="days col-xs-12 col-sm-2 col-md-1" id="days-nav">
    <ul class="nav nav-pills nav-stacked">
        @foreach(array_keys($postsByDay) as $day)
            <li>
                <a href="#{{ $day }}">{{ date("j.n.y", strtotime($day)) }}</a>
            </li>
        @endforeach
    </ul>
</nav>

<section class="col-xs-12 col-sm-10 col-md-11 posts">
    @foreach($postsByDay as $key => $day)
        <div class="clearfix">
            <h2 id="{{ $key }}">
                <a href="#{{ $key }}">{{ date("j.n.y", strtotime($key)) }}</a>
                <a href="#days-nav" class="btn btn-default"><span class="glyphicon glyphicon-triangle-top"></span> Up</a>
            </h2>

            @foreach($day as $post)
                <article class="post media" id="post-{{ $post->id }}">
                    <div class="media-left">
                        <a href="https://facebook.com/{{ $post->facebookPage->fb_id }}" target="_blank">
                            <img class="media-object" src="https://graph.facebook.com/{{ $post->facebookPage->fb_id }}/picture?type=square"
                                 alt="{{ $post->facebookPage->name }}">
                        </a>

                        Status:
                        @if($post->state === "found")
                            <span class="label label-info">Found</span>
                        @elseif($post->state === "evaluating")
                            <span class="label label-warning">Evaluating</span>
                        @elseif($post->state === "forbidden")
                            <span class="label label-danger">Forbidden</span>
                        @endif
                    </div>

                    <div class="media-body">
                        <h3 class="media-heading">
                            <a href="{{ $post->permalink_url }}" target="_blank">
                                Forbidden post by {{ $post->facebookPage->name }}
                            </a>
                        </h3>

                        <h4>Message</h4>
                        <p class="message">
                            {{ \GuzzleHttp\json_decode($post->message) }}
                        </p>

                        <h4>Forbidden words</h4>
                        @foreach(\GuzzleHttp\json_decode($post->banned_found) as $bannedString)
                            <span class="label label-danger">{{ $bannedString }}</span>
                        @endforeach

                        <div class="post-buttons">
                            <a href="{{ route('comments.show', $post->id) }}" class="btn btn-info comments-show">{{ $post->comments()->count() }} Comments</a>

                            @if($post->state !== "forbidden" && $post->deleted_at === null)
                                <form method="post" action="{{ route('posts.delete') }}" class="post-delete">
                                    {!! csrf_field() !!} {!! method_field('delete') !!}
                                    <input type="hidden" name="id" value="{{ $post->id }}"/>
                                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></button>
                                </form>
                            @endif

                            @if($post->deleted_at !== null)
                                <form method="post" action="{{ route('posts.restore') }}" class="post-restore">
                                    {!! csrf_field() !!} {!! method_field('put') !!}
                                    <input type="hidden" name="id" value="{{ $post->id }}"/>
                                    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                                </form>
                            @endif

                            @if($post->deleted_at === null && $post->state === "found")
                                <form method="post" action="{{ route('posts.evaluate') }}" class="post-evaluate">
                                    {!! csrf_field() !!} {!! method_field('put') !!}
                                    <input type="hidden" name="id" value="{{ $post->id }}"/>
                                    <button type="submit" class="btn btn-warning">Evaluate</button>
                                </form>
                            @endif

                            @if($post->deleted_at === null && $post->state === "evaluating")
                                <form method="post" action="{{ route('posts.forbid') }}" class="post-forbid">
                                    {!! csrf_field() !!} {!! method_field('put') !!}
                                    <input type="hidden" name="id" value="{{ $post->id }}"/>
                                    <button type="submit" class="btn btn-danger">Forbid</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endforeach
</section>