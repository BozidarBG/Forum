<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Profile;
use App\Http\Controllers\Controller;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Log;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $result=Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
//        if($result->errors()){
//            return redirect()->back()->with(['error'=>$result->errors()]);
//        }
          // Log::info($result->errors());


       return $result;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */

    protected function create(array $data)
    {

        //for hashid
        $lastId = User::count() ? User::latest()->first()->id : 0;
        $hashids = new Hashids('korisnici', 10);
        $hash_id = $hashids->encode($lastId + 1);

        return User::create([
            'hashid' => $hash_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $result=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);


        if(!$result->fails()){
            event(new Registered($user = $this->create($request->all())));

            if ($user) {
                $profile=new Profile();
                $profile->user_id=$user->id;
                $profile->save();
                $this->guard()->login($user);
                return $this->registered($request, $user)
                    ?: response()->json('success');
            } else {

                return response()->json('fail');
            }

        }else{

            return response()->json(['error', $result->errors()->all()]);
        }

    }
}
