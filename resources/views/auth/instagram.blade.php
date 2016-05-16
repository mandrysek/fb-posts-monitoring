@extends('layouts.auth')
@section('content')
    First you need too login to your Instagram account

    <a href="{{ $loginUrl }}" class="btn btn-default btn-info">
        <i class="fa fa-instagram"></i> Login to Instagram
    </a>

    @if(!empty($errors->all()))
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Oops!</strong> {{ $error }}
            </div>
        @endforeach
    @endif
@stop