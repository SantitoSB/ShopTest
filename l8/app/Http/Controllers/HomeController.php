<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showAll()
    {
        $categories = category::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('name', 'ASC')->get();
        $id = null;
        return view('index', compact('categories', 'products', 'id'));
    }
}
