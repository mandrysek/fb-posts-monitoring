@extends('layouts.master')
@section('root-content')

    <div class="main">
        <h1>Monitoring</h1>

        <nav>
            <ul class="nav nav-pills">
                @if(!auth()->user()->client)
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('pages') }}">Pages</a></li>
                    <li><a href="{{ route('posts.deleted') }}">Okay posts</a></li>
                @endif
                <li><a href="{{ route('posts.evaluating') }}">Evaluating posts</a></li>
                <li><a href="{{ route('posts.forbidden') }}">Forbidden posts</a></li>
            </ul>
        </nav>

        @yield('content')
    </div>

    <div class="lightbox">
        <div class="lightbox-content"></div>
        <div class="lightbox-loading"><i class="fa fa-spinner"></i></div>
    </div>
@stop