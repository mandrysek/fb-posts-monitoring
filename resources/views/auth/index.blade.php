@extends('layouts.auth')
@section('content')
    First you need too login to your facebook account

    <a href="{{ $loginUrl }}" class="btn btn-default btn-info">
        <i class="fa fa-facebook"></i> Login to Facebook
    </a>
    

    @if(!empty($errors->all()))
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Oops!</strong> {{ $error }}
            </div>
        @endforeach
    @endif
@stop