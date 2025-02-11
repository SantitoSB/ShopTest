@extends('layouts.main')

@section('title')
    Shop products
@endsection

@section('content')
        <div class="col-lg-3">

            <h1 class="my-4">Shop Name</h1>
            <div class="list-group">
                @foreach($categoriesList as $cat)
                    <a href="{{route('categories.show', $cat->id)}}" class="list-group-item  @if(isset($id) && $cat->id == $id) active @endif">{{$cat->name}}</a>
                @endforeach
            </div>

        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <div class="row">
                @foreach($productsList as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 text-center">
                                <a href="#"><img class="img-fluid" width="75" src="{{asset('storage/'.$product->photo)}}" alt=""></a>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="#">{{$product->name}}</a>
                                        </h4>
                                        <p>{{$product->description}}</p>
                                    <h5>${{$product->price}}</h5>
                                    <hr>
                                    Category: {{$categoryToShow->name}}
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->
@endsection
