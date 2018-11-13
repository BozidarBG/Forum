<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\User;

class Complaint extends Model
{
    //
    protected $fillable=['body', 'complained_to','complained_by', 'link'];

    public function getReportedUser(){
        return User::whereId($this->complained_to)->first();
    }

    public function getUserWhoComplained(){
        return User::whereId($this->complained_by)->first();
    }


}
