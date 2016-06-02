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


    @include('partials.posts')
@stop