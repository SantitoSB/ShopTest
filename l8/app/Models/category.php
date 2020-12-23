<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    protected static function boot() {
        parent::boot();

    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
