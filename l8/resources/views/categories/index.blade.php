@extends('layouts.main')

@section('title')
    Categories page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">Categories page</h1>
        <a href="{{route('categories.create')}}" class="btn btn-info">New category</a>
        <a href="{{route('showCategoryRestore')}}" class="btn btn-success">Restore category</a>
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
                    <td><a href="{{route('categories.edit', $category->id)}}" class="btn btn-primary">Edit</a>

                    <form action="{{route('categories.destroy', $category->id)}}" method="post" style="display: inline">
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="Delete" class="btn btn-warning" onclick="return confirm('Are you sure?')" />

                    </form>

                        <form action="{{route('categories.forceDestroy', $category->id)}}" method="post" style="display: inline">
                            @method('DELETE')
                            @csrf
                            <input type="submit" value="Force delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
