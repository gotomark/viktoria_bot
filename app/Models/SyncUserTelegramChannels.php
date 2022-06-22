<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncUserTelegramChannels extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','telegram_channel_id','user_telegram_channel_id'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sync_user_telegram_channels';

    public function userChannel(){

        return $this->hasMany( 'App\Models\UserTelegramChannels', 'id','user_telegram_channel_id');
    }

    public function userAlbums(){

        return $this->hasMany( 'App\Models\UserVkontakteAlbum', 'id','user_telegram_channel_id');
    }

    public function userChannelOne(){

        return $this->hasOne( 'App\Models\UserTelegramChannels', 'id','user_telegram_channel_id');
    }

    public function channel(){

        return $this->hasOne( 'App\Models\TelegramChannels', 'id','telegram_channel_id');
    }

}
