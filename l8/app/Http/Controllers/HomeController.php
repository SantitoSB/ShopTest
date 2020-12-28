<?php

namespace App\Http\Controllers;

use App\Models\category;


class HomeController extends BaseShopController
{
    public function showAll()
    {
        $categories = category::orderBy('name', 'ASC')->get();

        $products = $this->productRepository->getAll('name', 'ASC');
        $id = null;
        return view('index', compact('categories','products', 'id'));
    }
}
