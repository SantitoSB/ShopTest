<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];

        $eloquentCollection = Product::withTrashed()->get();

        $product = Product::withTrashed()->get()->first();

        dd($product->created_at->addMonth(5));

        //dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        $collection = collect($eloquentCollection->toArray());

        //dd(get_class($eloquentCollection), get_class($collection), $collection);

        //$result['first'] = $collection->first();
        //$result['last'] = $collection->last();

        //dd($collection->pluck('created_at'));

        //dd($result);
        $result['where']['data'] = $collection
            ->where('category_id', '=', 1)
            ->values() //чтобы сохранить такие же значения и ключи как в исходной коллекции
            ->keyBy('id');

        //dd($result);

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        $result['where']['first'] = $result['where']['data']->firstWhere('photo', '!=', null);

        //изменяем запись (но не ее саму, а ее копию)
        $result['map']['all'] = $collection->map(
            function ($item){
                $newItem = new \stdClass();
                $newItem->item_id = $item['id'];
                $newItem->item_name = $item['name'];
                $newItem->photo = is_null($item['photo']);

                return $newItem;
        });

        $result['map']['no_photo'] = $result['map']['all']
            ->where('photo', '=', 'null')
            ->values()
            ->keyBy('item_id');

        //изменяем саму запись
        $collection->transform(
            function ($item){
                $newItem = new \stdClass();
                $newItem->item_id = $item['id'];
                $newItem->item_name = $item['name'].$item['id'];
                $newItem->photo = is_null($item['photo']);
                $newItem->created_at = Carbon::parse($item['created_at']);
                $newItem->updated_at = Carbon::parse($item['updated_at']);
                $newItem->deleted_at = Carbon::parse($item['deleted_at']);
                return $newItem;
            });

        dd($collection);

        $newItem1 = new \stdClass();
        $newItem1->item_id = 1111;
        $newItem1->item_name = 'Test1';
        $newItem1->photo = false;

        $newItem2 = new \stdClass();
        $newItem2->item_id = 9999;
        $newItem2->item_name = 'Test9';
        $newItem2->photo = false;

        $collection->prepend($newItem1)->first();//добавили в начало и получили элемент
        $collection->push($newItem2)->last();//добавили в конец и получили элемент

        $collection->transform(
            function ($item){
                $newItem = new \stdClass();
                if($item->item_id == 1)
                {
                    $newItem->item_id = 7777;
                }
                else
                {
                    $newItem->item_id = $item->item_id;
                }

                $newItem->item_name = $item->item_name;
                $newItem->photo = is_null($item->photo);

                return $newItem;
            });

        $pulledItem = $collection->pull(1);//id - key в массиве

        //dd($collection, $pulledItem);

        $filtered = $collection->filter(function ($item)
        {
           $searchResult = mb_strpos($item->item_name, 'L');
           if($searchResult !== false)
           {
               return true;
           }

           return false;
        });

        //dd($filtered);

        $sortedCollection = $collection->sortBy('item_name'); //ключи сохранятются
        $sortedCollection = $collection->sortBy('item_name')->values();//ключи не сохраняются нумерация идет от нуля
        //$sortedCollection = $collection->sortByDesc('item_name');
        //dd($sortedCollection);

    }
}
