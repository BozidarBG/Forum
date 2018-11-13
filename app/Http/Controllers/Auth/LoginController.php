<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Hashids\Hashids;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        //\Log::info($request->all());
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            // Authentication passed...
            return response()->json('success');
        }else{
            return response()->json('fail');
        }
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {
        //userSocial is user object we get from facebook, github...
        $userSocial = Socialite::driver($provider)->user();

        //check if this user exists in our database (registered earlier via register form) and now
        //he wants to register with socials. if he exists, we log him in with his id and not with socials
        $user = User::where('email', $userSocial->user['email'])->first();
        if ($user) {
            if (Auth::loginUsingId($user->id)) {
                //user is logged in
                return response()->json('success');
            }
        }

        //user doesn't exists and we need to create user

        //we need hashid first
        $lastId = User::count() ? User::latest()->first()->id : 0;
        $hashids = new Hashids('korisnici', 10);
        $hash_id = $hashids->encode($lastId + 1);

        $userSignup = User::create([
            'name' => $userSocial['name'],
            'email' => $userSocial['email'],
            'hashid' => $hash_id,
            'avatar' => $userSocial->avatar,

        ]);

        //log the user in
        if ($userSignup) {
            if (Auth::loginUsingId($user->id)) {
                //user is logged in
                return response()->json('success');
            }
        }

    }
}
