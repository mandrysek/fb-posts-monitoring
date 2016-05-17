@extends('layouts.app')
@section('content')
    <div class="forbidden-posts">
        @foreach($deletedPosts as $forbiddenPost)
            <article class="forbidden-post media" id="forbidden-post-{{ $forbiddenPost->id }}">
                <div class="media-left">
                    <a href="https://facebook.com/{{ $forbiddenPost->facebookPage->fb_id }}" target="_blank">
                        <img class="media-object" src="https://graph.facebook.com/{{ $forbiddenPost->facebookPage->fb_id }}/picture?type=square"
                             alt="{{ $forbiddenPost->facebookPage->name }}">
                    </a>
                </div>
                <div class="media-body">
                    <h3 class="media-heading">
                        <a href="{{ $forbiddenPost->permalink_url }}" target="_blank">
                            Forbidden post by {{ $forbiddenPost->facebookPage->name }}
                        </a>
                    </h3>

                    <h4>Message</h4>
                    <p>
                        {{ \GuzzleHttp\json_decode($forbiddenPost->message) }}
                    </p>

                    <h4>Forbidden words</h4>
                    @foreach(\GuzzleHttp\json_decode($forbiddenPost->banned_found) as $bannedString)
                        <span class="label label-danger">{{ $bannedString }}</span>
                    @endforeach

                    <form method="post" action="{{ route('trash.restore') }}" class="forbidden-post-restore">
                        {!! csrf_field() !!} {!! method_field('put') !!}
                        <input type="hidden" name="id" value="{{ $forbiddenPost->id }}" />
                        <button type="submit" class="btn btn-warning">Post is bad!</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@stop