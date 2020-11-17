@extends('layouts.main')

@section('title')
    Categories page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">New Category</h1>
        <form action="{{route('categories.store')}}" method="POST">
            @csrf
            <br>
            Name:
            <input type="text" name="name" value="" class="form-control"/>
            <br/>
            <input type="submit" class="btn btn-primary" value="Save">
            <br/> <br/>
        </form>

    </div>
@endsection
