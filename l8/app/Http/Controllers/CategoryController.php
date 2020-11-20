<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;

class CategoryController extends BaseShopController
{
    /**
     * Display a listing of the resource.
     *
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoriesList = $this->categoryRepository->getAllForList('name', 'ASC');

        return view('categories.index', compact('categoriesList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        category::create(
            [
                'name' => $request->name
            ]);

        return redirect()->route('categories.index')->with('success', 'New category: '.$request->name.' successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $categoriesList = $this->categoryRepository->getAllForList('name');
        $categoryToShow = $this->categoryRepository->getByID($id);


        if(is_null($categoryToShow))
        {
            return redirect()->route('home')->withErrors(['msg'=> 'Error. Can\'t find category with id = '.$id]);
        }

        $productsList = $this->productRepository->getProductsByCategory($categoryToShow->id, 'name', 'ASC');


        return view('categories.view', compact('categoriesList', 'productsList', 'categoryToShow'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->getByID($id);

        if(is_null($category))
        {
            return redirect()->route('categories.index')->withErrors(['msg' => 'Can\'t find category with id = '.$id]);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryRepository->getByID($id);

        $category->update([
            'name' => $request->name
            ]);

        return redirect()->route('categories.index')->with('success', 'Category: '.$request->name.' was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $category = $this->categoryRepository->getByID($id);
        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category: '.$name.' successfully deleted (can be restored)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy(int $id)
    {
        $category = $this->categoryRepository->getByID($id);
        $name = $category->name;
        $category->forceDelete();

        return redirect()->route('categories.index')->with('success', 'Category: '.$name.' successfully deleted (forever)');
    }

    /**
     * @return
     *
     * Show restore page
     */
    public function showRestore()
    {
        $categories = $this->categoryRepository->getAllTrashed();

        return view('categories.restore', compact('categories'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $id)
    {
        $category = $this->categoryRepository->getConditionalTrashed('id', '=', $id);

        //If $category did not find
        if(is_null($category))
        {
            return redirect()->route('categories.index')->withErrors((['msg'=> 'Can\'t find category for restore with id = '.$id]));
        }

        $category->restore();

        return redirect()->route('categories.index');
    }

}
