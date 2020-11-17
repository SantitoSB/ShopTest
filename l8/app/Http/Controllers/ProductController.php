<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsPaginator = Product::orderBy('name')->paginate(5);//количество записей на странице
        $categories = category::all(['id', 'name']);

        return view('products.index', compact('productsPaginator', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = category::all();//получаем категории для передачи в форму
        return view('products.create', compact('categories'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {

        $product = Product::findOrFail($id);


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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *  @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        $product = Product::findOrFail($id);

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
        $products = Product::onlyTrashed()->get();
        $allCategories = category::withTrashed()->get();
        return view('products.restore', compact('products', 'allCategories'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->where('id', '=', $id)->get()->first();

        $categoriesTrashed = category::onlyTrashed()->where('id', '=', $product->category_id)->get()->first();


        if(!$categoriesTrashed)//category exist
        {
            $product->restore();
        }
        else
        {
            $unknownCategory = category::withoutTrashed()->where('name', '=', 'UNKNOWN')->get()->first();
            if(!$unknownCategory)
            {
                $unknownCategory = new category();
                $unknownCategory->name = 'UNKNOWN';
                $unknownCategory->save();
            }
            $product->update(['category_id'=>$unknownCategory->id]);
            $product->restore();

            return redirect()->route('products.index')->with('success', 'Product: '.$product->name.' successfully restored! And set to UNKNOWN category!');
        }

        return redirect()->route('products.index')->with('success', 'Product: '.$product->name.' successfully restored!');
    }
}
