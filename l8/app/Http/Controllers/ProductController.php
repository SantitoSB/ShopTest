<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

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
        //$categoriesList = $this->categoryRepository->getAllForList();

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

        if($request->hasFile('photo'))
        {
            $user = auth()->user();
            $user_id = $user->getAuthIdentifier();

            $path = $request->file('photo')->store('photos/'.$user_id, 'public');

            Product::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
                'photo'=>$path
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product: '.$request->name.' successfully created!');
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
            return redirect()->route('products.index')->withErrors(['msg' => 'Can\'t find product with id = '.$id]);
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
            return redirect()->route('home')->withErrors(['msg' => 'Can\'t find product with id = '.$id]);
        }

        if($request->hasFile('photo')) {
            //удаляем файл картинки
            if ($product->photo !== null) {
                if (Storage::disk('public')->exists($product->photo)) {
                    Storage::disk('public')->delete($product->photo);
                }
            }

            $user = auth()->user();
            $user_id = $user->getAuthIdentifier();

            $path = $request->file('photo')->store('photos/' . $user_id, 'public');
        }
        else
        {
            $path = $product->photo;
        }

        $product->update(
            [
                'name'=>$request->name,
                'description'=>$request->description,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
                'photo'=>$path
            ]);

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

        $product->delete();

        return redirect()->route('products.index');
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

        //удаляем фотографию
        if ($product->photo !== null) {
            if (Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
        }
        //удаляем запись из базы
        $product->forceDelete();

        return redirect()->route('products.index');
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
