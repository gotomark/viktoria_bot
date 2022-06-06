<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTelegramChannels extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','channel_id','title'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_telegram_channels';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function syncTelegramChannels()
    {
        return $this->hasMany('App\Models\SyncUserTelegramChannels', 'user_telegram_channel_id', 'id');
    }

}
