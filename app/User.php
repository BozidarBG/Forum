<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Helper;

class User extends Authenticatable
{
    use Notifiable, Helper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'hashid', 'avatar', 'banned_until'
    ];

    public $path='/public/uploads/avatars/';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin(){
        return $this->admin===1;
    }

    public function getAvatar(){
        return $this->avatar !=null ? $this->avatar : '/images/no-user.png' ;
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function likereplies(){
        return $this->hasMany(Likereply::class);
    }
}
