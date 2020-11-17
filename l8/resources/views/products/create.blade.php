@extends('layouts.main')

@section('title')
    Product page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">New Product</h1>

        <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <br>
            Name:
            <input type="text" name="name" value="{{old('name')}}" class="form-control"/>
            <br/>
            Price:
            <input type="text" name="price" value="{{old('price')}}" class="form-control"/>
            <br/>
            Description:
            <textarea name="description" class="form-control">{{old('description')}}</textarea>
            <br/>
            Category:
            <select name="category_id" class="form-control">
                @foreach($categories as $cat)
                    <option value="{{$cat->id}}" @if($cat->id == old('category_id')) selected @endif>{{$cat->name}}</option>
                @endforeach
            </select>
            <br/>
            Photo:
            <br/>
            <input type="file" name="photo"/>
            <br/><br/>
            <input type="submit" class="btn btn-primary" value="Save">
            <br/><br/>
        </form>

    </div>
@endsection
