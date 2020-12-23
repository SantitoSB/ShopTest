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


    public function getNameValueAttribute()
    {
        if($this->price > 100)
        {
            $result = '!!!'.$this->name.'!!!';
            return $result;
        }
        return $this->name;
    }

    //преобразование
    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
        'updated_at' => 'datetime:d/m/Y',
        'deleted_at' => 'datetime:d/m/Y',
    ];

    /**
     * Accessor изменяет название продукта
     *
     * @param $nameFromModel
     * @return false|string|string[]
     */
    public function getNameAttribute($nameFromModel)
    {
        return mb_strtoupper($nameFromModel);
    }

    /**
     * Mutator изменяет хранение объекта
     *
     * @param $incomingName
     */
    public function setNameAttribute($incomingName)
    {
        $this->attributes['name'] = mb_strtolower($incomingName);
    }

}
