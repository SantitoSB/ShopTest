@extends('layouts.main')

@section('title')
    Product page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">Product edit</h1>

        <form action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <br>
            Name:
            <input type="text" name="name" value="{{$product->name}}" class="form-control"/>
            <br/>
            Price:
            <input type="text" name="price" value="{{$product->price}}" class="form-control"/>
            <br/>
            Description:
            <textarea name="description" class="form-control">{{$product->description}}</textarea>
            <br/>
            Category:
            <select name="category_id" class="form-control">
                @foreach($categoriesList as $cat)
                    <option value="{{$cat->id}}" @if($cat->id == $product->category_id) selected @endif>{{$cat->name}}</option>
                @endforeach
            </select>
            <div class="row">
                <div class="col-1">
                    Photo:
                    <img src="{{asset('storage/'.$product->photo)}}" class="img-thumbnail" alt="">
                    <br/>
                    <br/>
                    <input type="file" name="photo" value=""/>
                </div>
            </div>

            <br/><br/>
            <input type="submit" class="btn btn-primary" value="Update">
            <br/> <br/>
        </form>

    </div>
@endsection
