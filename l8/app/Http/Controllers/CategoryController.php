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

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryRepository = new CategoryRepository();
        $categoriesList = $categoryRepository->getAll('name', 'ASC');

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoryRepository = new CategoryRepository();
        $categoriesList = $categoryRepository->getAll('name');

        $categoryToShow = $categoryRepository->getByID($id);
        if(empty($categoryToShow))
        {
            //TODO CategoryController.show: Error can't find category with id!
        }

        $productsRepository = new ProductsRepository();
        $productsList = $productsRepository->getProductsByCategory($categoryToShow->first()->id, 'name', 'ASC');


        return view('categories.view', compact('categoriesList', 'productsList', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat = category::findOrFail($id);

        return view('categories.edit', compact('cat'));
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
        $category = category::findOrFail($id);
        $category->update([
            'name' => $request->name
            ]);
        //$category->name = $request->input('name');
        //$category->save();


        return redirect()->route('categories.index')->with('success', 'Category: '.$request->name.' was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = category::findOrFail($id);
        $name = $category->name;

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category: '.$name.' successfully deleted (can be restored)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        $category = category::findOrFail($id);
        $name = $category->name;
        $category->forceDelete();

        return redirect()->route('categories.index')->with('success', 'Category: '.$name.' successfully deleted (forever)');
    }

    /**
     * Restore Category page
     */
    public function showRestore()
    {
        $categories = category::onlyTrashed()->get();

        return view('categories.restore', compact('categories'));
    }

    public function restore($id)
    {
        category::onlyTrashed()->where('id', '=', $id)->get()->first()->restore();
        return redirect()->route('categories.index');

    }

}
