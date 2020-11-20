@extends('layouts.main')

@section('title')
    Categories page
@endsection


@section('content')
    <div class="col-lg-12">
        <h1 class="my-4">Products restore page</h1>
        <br/><br/>
        <table class="table">
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Category</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($productsList as $product)
                <tr>
                    <td>{{$product->name}}</td>
                    @foreach($categoriesList as $cat)
                        @if($product->category_id == $cat->id)
                            <td>{{$cat->name}}
                            @if($cat->deleted_at !== null)
                                    <b>(current status: TRASHED)</b>
                            @endif
                            </td>
                        @endif
                    @endforeach
                    <td><a href="{{route('productRestore', $product->id)}}" class="btn btn-success">Restore</a>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
