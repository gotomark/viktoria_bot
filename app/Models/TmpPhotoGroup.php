<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpPhotoGroup extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','media_group_id','channel_id','caption'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tmp_photo_group';
}
