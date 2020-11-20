@extends('layouts.main')

@section('title')
    Product page
@endsection


@section('content')
    <div class="col-md-12">
        <h1 class="my-4">Product page</h1>
        <a href="{{route('products.create')}}" class="btn btn-info">New product</a>
        <a href="{{route('showProductRestore')}}" class="btn btn-success">Restore product</a>
        <br/> <br/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Photo</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($productsPaginator as $product)
                <tr>
                    <td>{{$product->name}}</td>
                    @foreach($categoriesList as $cat)
                        @if($cat->id == $product->category_id)
                            <td>{{$cat->name}}</td>
                            @continue;
                        @endif
                    @endforeach

                    <td>${{$product->price}}</td>
                    <td><img src="{{asset('storage/'.$product->photo)}}" width="70px" alt=""></td>
                    <td><a href="{{route('products.edit', $product->id)}}" class="btn btn-primary">Edit</a>

                    <form action="{{route('products.destroy', $product->id)}}" method="post" style="display: inline">
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="Delete" class="btn btn-warning" onclick="return confirm('Are you sure?')" />
                    </form>

                        <form action="{{route('products.forceDestroy', $product->id)}}" method="post" style="display: inline">
                            @method('DELETE')
                            @csrf
                            <input type="submit" value="Force delete" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                        </form>

                    </td>
                </tr>
            @endforeach
        </table>

    @if($productsPaginator->total() > $productsPaginator->count())
            <div class="row">
                <div class="col-md-12 ">
                        {{$productsPaginator->links()}}
                </div>
            </div>
    @endif
    </div>

@endsection
