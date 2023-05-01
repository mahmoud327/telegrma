<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryUserScore extends Model
{
    protected $fillable = [
        'chat_id', 'name','type_answer'
    ];

    protected $table="history_user_scores";


}
