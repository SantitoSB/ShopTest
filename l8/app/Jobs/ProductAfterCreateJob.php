<?php

namespace App\Jobs;

use App\Models\Product;
use App\Notifications\NewProductCreated;
use App\Notifications\NewProductCreatedDiscord;
use App\Notifications\NewProductCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProductAfterCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        //$this->onQueue('Notify');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->product->notify(new NewProductCreated($this->product));
        $this->product->notify(new NewProductCreatedDiscord($this->product));
        $this->product->notify(new newProductCreatedMail($this->product));
        Log::channel('actionmsg')->info('New product created. ID: '.$this->product->id.' Name: '.$this->product->name.' Category_ID: '.$this->product->category_id);
    }
}
