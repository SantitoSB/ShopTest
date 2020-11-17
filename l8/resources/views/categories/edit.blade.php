@extends('layouts.main')

@section('title')
    Categories page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">Category edit</h1>
        <form action="{{route('categories.update', $cat->id)}}" method="POST">
            @method('PUT')
            @csrf
            <br>
            Name:
            <input type="text" name="name" value="{{$cat->name}}" class="form-control"/>
            <br/>
            <input type="submit" class="btn btn-primary" value="Update">
            <br/> <br/>
        </form>

    </div>
@endsection
