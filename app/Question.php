<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\Helper;
use Auth;

class Question extends Model
{
    use Sluggable, Helper;
    //
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function countReplies(){
        return $this->replies->count();
    }

    public function countLikes(){
        return $this->likes->count();
    }

    public function likes(){
        return $this->hasMany(Like::class);
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
