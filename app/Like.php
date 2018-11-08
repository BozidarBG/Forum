<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


//model for like questions
class Like extends Model
{
    protected $fillable=['user_id', 'question_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }


}
