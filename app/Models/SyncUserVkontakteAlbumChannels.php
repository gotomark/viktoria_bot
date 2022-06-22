<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncUserVkontakteAlbumChannels extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','telegram_channel_id','user_vkontakte_album_id'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sync_user_vkontakte_albums';

    public function userAlbums(){

        return $this->hasMany( 'App\Models\UserVkontakteAlbum', 'id','user_vkontakte_album_id');
    }

    public function userAlbumOne(){

        return $this->hasOne( 'App\Models\UserVkontakteAlbum', 'id','user_vkontakte_album_id');
    }

    public function channel(){

        return $this->hasOne( 'App\Models\TelegramChannels', 'id','telegram_channel_id');
    }

}
