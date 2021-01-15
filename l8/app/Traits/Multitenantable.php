<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Multitenantable {
    protected static function bootMultitenantable()
    {

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by_user_id = Auth::user()->id;
            }
        });

        static::addGlobalScope('created_by_user_id', function (Builder $builder) {
            if (Auth::user()->id != 1) {
                $builder->where('created_by_user_id', Auth::user()->id);
            }
        });

    }

}
