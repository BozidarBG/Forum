<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Helper;
use Auth;

class Reply extends Model
{
    use Helper;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function likes(){
        return $this->hasMany(Likereply::class);
    }

    //return true or false, if reply was liked by auth user
    public function is_liked_by_auth_user(){
        $id=Auth::id();
        $likers=[];
        //take all likes and put them in array. return true if auth user id is in that array
        foreach($this->likes as $like){
            $likers[]=$like->user_id;
        }
        return in_array($id, $likers);
    }
}
