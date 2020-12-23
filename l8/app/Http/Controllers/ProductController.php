<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\category;
use App\Models\Product;

class ProductController extends BaseShopController
{


    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsPaginator = $this->productRepository->getAllWithPaginate(10, 'name');//количество записей на странице

        return view('products.index', compact('productsPaginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get categories list
        $categoriesList = $this->categoryRepository->getAllForList('name');

        return view('products.create', compact('categoriesList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $data = $request->all();
        $product = Product::create($data);

        if($product)
        {
            return redirect()->route('products.index')->with('success', 'Product: '.$request->name.' successfully created!');
        }
        else
        {
            return back()->withErrors(['msg' => 'Can\'t save product with name = '.$request->name])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $product = $this->productRepository->getByID($id);

        if(is_null($product))
        {
            return back()->withErrors(['msg' => 'Can\'t find product with id = '.$id])->withInput();
        }

        $categoriesList = $this->categoryRepository->getAllForList('name');

        return view('products.edit', compact('product', 'categoriesList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productRepository->getByID($id);

        if(is_null($product))
        {
            return back()->withErrors(['msg' => 'Can\'t find product with id = '.$id])->withInput();
        }

        $data = $request->all();

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product: '.$request->name.' successfully updated');

    }

    /**
     * Soft remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $product = $this->productRepository->getByID($id);

        if(is_null($product))
        {
            return redirect()->route('products.index')->withErrors(['msg' => 'Can\'t find product with id = '.$id]);;
        }

        //удаляем запись из базы
        $result = $product->forceDelete();

        if($result)
        {
            return redirect()->route('products.index')->with('success', 'Products count: '.$result.' successfully deleted');
        }
        else
        {
            return back()->withErrors(['msg' => 'Unknown error. Failed to delete product with id = '.$id]);;
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy(int $id)
    {
        $product = $this->productRepository->getByID($id);

        if(is_null($product))
        {
            return redirect()->route('products.index')->withErrors(['msg' => 'Can\'t find product with id = '.$id]);
        }

        //удаляем запись из базы
        $result = $product->forceDelete();

        if($result)
        {
            return redirect()->route('products.index')->with('success', 'Products count: '.$result.' successfully deleted');
        }
        else
        {
            return back()->withErrors(['msg' => 'Unknown error. Failed to delete product with id = '.$id]);;
        }
    }


    /**
     * Restore Category page
     */
    public function showRestore()
    {
        $productsList = $this->productRepository->getAllTrashed('name');
        $categoriesList = $this->categoryRepository->getAllWithTrashed();

        return view('products.restore', compact('productsList', 'categoriesList'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * Restore product
     */
    public function restore(int $id)
    {
        $product = $this->productRepository->getConditionalTrashed('id', '=', $id);

        if(is_null($product))
        {
            return redirect()->route('products.index')->withErrors(['msg' => 'Can\'t find product with id = '.$id]);;
        }

        $categoryExist = $this->categoryRepository
            ->getConditionalAll('id', '=', $product->category_id);


        if(!is_null($categoryExist))//category exist
        {
            $product->restore();
        }
        else
        {
            //check if unknown category exist in trashed
            $unknownCategoryExistInAll = $this->categoryRepository
                ->getConditionalAll('name', '=', 'UNKNOWN');

            $unknownCategoryExistInTrashed = $this->categoryRepository
                ->getConditionalTrashed('name', '=', 'UNKNOWN');

            if(is_null($unknownCategoryExistInAll) && is_null($unknownCategoryExistInTrashed))
            {
                $unknownCategory = new category();
                $unknownCategory->name = 'UNKNOWN';
                $unknownCategory->save();
                $unknownCategoryID = $unknownCategory->id;
            }
            else if(is_null($unknownCategoryExistInAll) && !is_null($unknownCategoryExistInTrashed))
            {
                //restore category without cascade
                $unknownCategoryExistInTrashed->deleted_at = null;
                $unknownCategoryExistInTrashed->save();

                $unknownCategoryID = $unknownCategoryExistInTrashed->id;
            }
            else
            {
                $unknownCategoryID = $unknownCategoryExistInAll->id;
            }

            $product->update(['category_id'=>$unknownCategoryID]);
            $product->restore();

            return redirect()->route('products.index')->with('success', 'Product: '.$product->name.' successfully restored! And set to UNKNOWN category!');
        }

        return redirect()->route('products.index')->with('success', 'Product: '.$product->name.' successfully restored!');
    }
}
