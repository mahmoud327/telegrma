<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    protected $fillable = [
        'chat_id', 'name','points','correct_answers','tries','status','number_question'
    ];

    protected $table="user_scores";


}
