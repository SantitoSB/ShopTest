<?php

namespace App\Observers;

use App\Jobs\ProductAfterCreateJob;
use App\Models\Product;
use App\Notifications\newProductCreated;
use App\Notifications\NewProductCreatedDiscord;
use App\Notifications\NewProductCreatedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{

    /**
     * @param Product $product
     *  Call BEFORE CREATE
     * return void
     */
    public function creating(Product $product)
    {
        if(!is_null($product->photo))
        {
            $product->photo = $this->savePhotoToDisk($product->photo);
        }
    }

    /**
     * Handle the product "created" event.
     *
     * Call AFTER CREATE
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $product->notify(new NewProductCreated($product));
        Log::critical('New product created. ID: '.$product->id.' Name: '.$product->name.' Category_ID: '.$product->category_id);
        Log::channel('actionmsg')->info('New product created. ID: '.$product->id.' Name: '.$product->name.' Category_ID: '.$product->category_id);

    }



    /**
     * @param Product $product
     *
     * Call before save, after update, create etc.
     */
    public function updating(Product $product)
    {
        //check changes
        if($product->isDirty('photo')) {
            $originalPhoto = $product->getOriginal('photo');
            //удаляем файл картинки
            $this->forceDeletePhoto($originalPhoto);

            $product->photo = $this->savePhotoToDisk($product->photo);
        }

    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {

    }

    /**
     * Call before delete
     * @param Product $product
     */
    public function deleting(Product $product)
    {
        if($product->isForceDeleting())
        {
            $this->forceDeletePhoto($product->photo);
        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {

    }



    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {

    }


    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {

    }


    /**
     * Function save photo file to disk
     * @param $photoFile
     * @return mixed
     */
    private function savePhotoToDisk($photoFile)
    {
        $user = auth()->user();
        $user_id = $user->getAuthIdentifier();

        $path = $photoFile->store('photos/'.$user_id, 'public');
        return $path;
    }


    /**
     * Function for delete photo from disk
     * @param $filePath
     */
    private function forceDeletePhoto($filePath)
    {
        if(!is_null($filePath))
        {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }
}
