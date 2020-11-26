<?php


namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\ProductsRepository;

class BaseShopController extends Controller
{
    /**
     * @var CategoryRepository
     * Repository for categories
     */
    protected $categoryRepository;

    /**
     * @var ProductsRepository
     * Repository for products
     */
    protected $productRepository;

    /**
     * BaseShopController constructor.
     */
    public function __construct()
    {
        $this->categoryRepository = app(categoryRepository::class);
        $this->productRepository = app(ProductsRepository::class);
    }


}
