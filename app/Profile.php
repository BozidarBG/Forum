<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Helper;

class Profile extends Model
{
    use Helper;
    //
    protected $fillable=['about', 'country_id', 'city', 'web', 'email', 'job'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
