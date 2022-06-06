<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebHookHistory extends Model
{
    use HasFactory;

    protected $fillable = ['json_data'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'web_hook_history';
}
