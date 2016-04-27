@extends('layouts.master')
@section('scripts')
    <link rel="stylesheet" href="{{ elixir('css/auth.css') }}">
@endsection
@section('styles')
    <script src="{{ elixir('js/auth.js') }}"></script>
@endsection
@section('root-content')
    <div class="auth">
        @yield('content')
    </div>
@stop