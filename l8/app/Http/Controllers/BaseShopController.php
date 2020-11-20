<?php


namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\ProductsRepository;

class BaseShopController extends Controller
{
    /**
     * @var ProductsRepository
     */
    protected $productRepository;

    /**
     * @var CategoryRepository
     * Repository for categories
     */
    protected $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = app(categoryRepository::class);
        $this->productRepository = app(ProductsRepository::class);
    }


}
