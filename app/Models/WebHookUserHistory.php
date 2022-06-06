<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebHookUserHistory extends Model
{
    use HasFactory;
    protected $fillable = ['json_data','user_id'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'web_hook_user_history';
}
