<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Traits\Multitenantable;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    use Multitenantable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'photo',
        'deleted_at',
        'created_by_user_id'];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
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

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('app.slack_webhook');
    }

    /**
     * Route notifications for the Discord channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForDiscord($notification)
    {
        return config('app.discord_webhook');
    }

    public function routeNotificationForMail($notification)
    {
        return 'santosb@mail.ru';
    }

}
