<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['name', 'description', 'price', 'category_id', 'photo', 'deleted_at'];

    public function category()
    {
        return $this->belongsTo(category::class);
    }



}
