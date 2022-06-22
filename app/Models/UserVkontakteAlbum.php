<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVkontakteAlbum extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','album_id','title'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_vkontakte_album';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function syncAlbums()
    {
        return $this->hasMany('App\Models\SyncUserVkontakteAlbumChannels', 'user_telegram_channel_id', 'id');
    }

}
