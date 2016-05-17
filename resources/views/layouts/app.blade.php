@extends('layouts.master')
@section('root-content')

    <div class="main">
        <h1>Monitoring</h1>

        <nav>
            <ul class="nav nav-pills">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('trash') }}">Trash</a></li>
                <li><a href="{{ route('pages') }}">Pages</a></li>
            </ul>
        </nav>

        @yield('content')
    </div>
@stop