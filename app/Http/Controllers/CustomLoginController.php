<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    //use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function loginUser(Request $request)
    {
        ///\Log::warning($request->toArray());
        $email = $request->email;
        $password = $request->password;
        $rememberToken = $request->remember;

        //use auth to authenticate user


        $user = User::where(['email' => $email])->first();

        if ($user->password=== Hash::make($password)) {

            Auth::login($user);
            //\Log::warning($user);
            $msg = [
                'status' => 'success',
                'message' => 'Login successful'
            ];
        } else {
            $msg = [
                'status' => 'error',
                'message' => 'Login fail'
            ];
        }
        return response()->json([$msg]);


    }
}
