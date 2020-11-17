@extends('layouts.main')

@section('title')
    Categories page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">Categories page</h1>
        <br/><br/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td><a href="{{route('categoryRestore', $category->id)}}" class="btn btn-success">Restore</a>

                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
