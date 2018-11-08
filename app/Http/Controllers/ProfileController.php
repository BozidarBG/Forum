<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Profile;
use Illuminate\Support\Facades\Session;
use Validator;
use Log;
use File;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showUser']);
    }

    public function showMyProfile()
    {
        return view('profile')->with('user', Auth::user());
    }

    public function showUser($hashid)
    {
        return view('profile')->with('user', User::where('hashid', $hashid)->first());
    }


    public function edit()
    {
        return view('edit-profile')
            ->with('user', Auth::user())
            ->with('countries', \App\Country::all());
    }


    public function updateAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|max:1000',
        ]);

        if ($validator->fails()) {

            return $validator->errors();
        }

        $status = "";

        if ($request->hasFile('avatar')) {
            $user=Auth::user();
            $image = $request->file('avatar');
            // Rename image
            $filename = time().$user->hashid . '.' . $image->guessExtension();

//            $path = $request->file('avatar')->storeAs(
//                'avatars', $filename
//            );
            $image->move('uploads/avatars', $filename);

            $status = "uploaded";
            //demo je slika. tako je sve nazvao pa i ja nazvao model i kontroler tako

            //ako hoćemo samo jednu sliku da imamo, tj avatar, prvo treba da obrišemo postojeću
            //pa onda da usnimimo novu

            $image = isset($user->avatar) ? $user->avatar : null;

            if ($image) {
                $path = parse_url($user->avatar);
                File::delete(public_path($path['path']));
                //$image->delete();
            }

            $user->avatar = 'uploads/avatars/'.$filename;
            if ($user->save()) {
                return response($status, 200);
            } else {
                return response($status, 500);
            }

        }
    }

    public function updateProfile(Request $request){

        //about country (dobija id) city email web job
        $profile=Profile::where('user_id', Auth::id())->first();

        $profile->about=$request->about;
        $profile->country_id=$request->country_id;
        $profile->city=$request->city;
        $profile->email=$request->email;
        $profile->web=$request->web;
        $profile->job=$request->job;
        if($profile->save()){
            Session::flash('success', 'Profile updated successfully!');
            return redirect()->route('my.profile');
        }else{
            Session::flash('error', 'There was some error on the server!');
            return redirect()->back();
        }

    }

    public function destroy($id)
    {
        //
    }
}
