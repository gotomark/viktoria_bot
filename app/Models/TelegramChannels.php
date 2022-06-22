<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChannels extends Model
{
    use HasFactory;
    protected $fillable = ['channel_id','title'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'telegram_channels';
    public $dates = ['created_at'];

    public function userSyncChannels(){

        return $this->hasMany( 'App\Models\SyncUserTelegramChannels', 'telegram_channel_id','id');
    }
    public function userSyncAlbums(){

        return $this->hasMany( 'App\Models\SyncUserVkontakteAlbumChannels', 'telegram_channel_id','id');
    }


}
