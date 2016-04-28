@extends('layouts.app')
@section('content')
    <div class="panels clearfix">
        @include('partials.pages.add')
        @include('partials.bannedstrings.create')
    </div>

    @if(session()->has('success_message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Yey!</strong> {{ session('success_message') }}
        </div>
    @endif

    @if(!empty($errors->all()))
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Oops!</strong> {{ $error }}
            </div>
        @endforeach
    @endif


    <div class="forbidden-posts">
        @foreach($forbiddenPosts as $forbiddenPost)
            <article class="forbidden-post media" id="forbidden-post-{{ $forbiddenPost->id }}">
                <div class="media-left">
                    <a href="https://facebook.com/{{ $forbiddenPost->facebookPage->fb_id }}" target="_blank">
                        <img class="media-object" src="http://graph.facebook.com/{{ $forbiddenPost->facebookPage->fb_id }}/picture?type=square"
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

                    <form method="post" action="{{ route('deleteForbiddenPost') }}" class="forbidden-post-delete">
                        {!! csrf_field() !!} {!! method_field('delete') !!}
                        <input type="hidden" name="id" value="{{ $forbiddenPost->id }}" />
                        <button type="submit" class="btn btn-success">Post is okay!</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@stop