<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Carbon\Carbon;
use Log;
use Session;

class Banned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=Auth::user();
        Log::info($user);
        if($user->banned){
            //check if ban has expired
            if($user->banned_until < Carbon::now()){
                //ban has expired
                Log::info($user);
                $user->banned=0;
                $user->banned_until=null;
                $user->save();
                return $next($request);
            }else{
                //ban has not expired
                if($request->ajax()){
                    return response()->json(['error'=>'You are banned']);
                }else{
                    Session::flash('error', 'You are banned');
                    return redirect()->back();
                }

            }
        }else{
            return $next($request);
        }

    }
}
