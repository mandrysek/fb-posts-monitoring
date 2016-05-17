@extends('layouts.app')
@section('content')
    <div class="pages">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Facebook Id</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>{{ $page->fb_id }}</td>
                    <td>{{ $page->name }}</td>
                    <td>
                        <div class="btn-group">
                            @if($page->deleted_at === null)
                                <form method="post" action="{{ route('pages.delete') }}" class="page-delete">
                                    {!! csrf_field() !!} {!! method_field('delete') !!}
                                    <input type="hidden" name="id" value="{{ $page->id }}"/>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @else
                                <form method="post" action="{{ route('pages.restore') }}" class="page-restore">
                                    {!! csrf_field() !!} {!! method_field('put') !!}
                                    <input type="hidden" name="id" value="{{ $page->id }}"/>
                                    <button type="submit" class="btn btn-success">Restore</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop