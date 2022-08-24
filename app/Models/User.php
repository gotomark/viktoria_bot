<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'flag_caption'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userChannels(){
        return $this->hasMany( 'App\Models\UserTelegramChannels', 'user_id','id');
    }
    public function userAlbums(){
        return $this->hasMany( 'App\Models\UserVkontakteAlbum', 'user_id','id');
    }
    public function syncTelegramChannels(){
        return $this->hasMany( 'App\Models\SyncUserTelegramChannels', 'user_id','id');
    }
    public function syncAlbums(){
        return $this->hasMany( 'App\Models\SyncUserVkontakteAlbumChannels', 'user_id','id');
    }

    public function isAdmin(){
        return $this->role_id == 1 ? true : false;
    }
}
