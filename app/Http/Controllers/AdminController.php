<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Complaint;
use App\Reply;
use App\User;
use Illuminate\Support\Carbon;
use Session;


class AdminController extends Controller
{
    public function dashboard(){

        return view('admin.dashboard')
            ->with('users', User::orderBy('created_at', 'desc')->take(5)->get())
            ->with('questions', Question::orderBy('created_at', 'desc')->take(5)->get())
            ->with('complaints', Complaint::orderBy('created_at', 'desc')->take(5)->get());
    }

    //displays all questions to check if they comply with rules
    public function questions(){
        return view('admin.questions')->with('questions', Question::orderBy('id', 'desc')->paginate(15));
    }

    //admin can delete question if it doesn't comply with rules
    public function deleteQuestion(Question $question){
        //first we delete replies if any
        if($question->replies->count()>0){
            foreach($question->replies as $reply){
                $reply->delete();
            }
        }

        if($question->delete()){
            //we detach tags from question_tag table
            $question->tags()->detach();
            return response()->json(['success'=>'Question deleted successfully!']);
        }else{
            return response()->json(['error' =>'Question was not deleted!']);
        }

    }

    //displays all replies to check if they comply with rules
    public function replies(){
        return view('admin.replies')->with('replies', Reply::orderBy('id','desc')->paginate(15));
    }

    //admin can delete reply if it doesn't comply with rules
    public function deleteReply(Reply $reply){
        if($reply->delete()){
            return response()->json(['success'=>'Reply deleted successfully!']);
        }else{
            return response()->json(['error' =>'Reply was not deleted!']);
        }
    }

    public function complaints(){
        return view('admin.complaints')->with('complaints', Complaint::orderBy('created_at', 'desc')->paginate(10));
    }

    public function deleteComplaint(Request $request, Complaint $complaint){

        if($complaint->delete()){
            Session::flash('success', 'Complaint deleted!');
            return redirect()->back();
        }else{
            Session::flash('error', 'Complaint deleted!');
            return redirect()->back();
        }
    }

    public function users(){
        return view('admin.users')->with('users', User::where('admin', '!=', 1)->orderBy('created_at', 'desc')->paginate(10));
    }

    public function banUser(Request $request){
        //Log::info($request->all());
        $this->validate($request, [
            'id'=>'required',
            'time'=>'required'
        ]);
        $user=User::find($request->id);

        //we can't ban admin
        if($user->isAdmin()){
            return response()->json(['error'=> 'You can\'t ban admins!']);
        }

        if($user){
            //if time ==0 unban user, else ban it for given time
            if($request->time==0){
                $user->banned=0;
                $user->banned_until=null;
                if($user->save()){
                    return response()->json(['success'=> $user->name." is not banned anymore"]);
                }else{
                    return response()->json(['error'=> 'There was some error']);
                }

            }elseif($request->time==10){
                //user is banned forever
                $user->banned=3;
                $user->banned_until=Carbon::parse(Carbon::now()->addYear(50));
                if($user->save()){
                    return response()->json(['success'=> $user->name." is banned forever"]);
                }else{
                    return response()->json(['error'=> 'There was some error']);
                }
            }else{
                $user->banned=1;
                $user->banned_until=Carbon::now()->addDays($request->time);
                if($user->save()){
                    return response()->json([
                        'success'=> $user->name." is banned",
                        'date'=>$user->banned_until
                    ]);
                }else{
                    return response()->json(['error'=> 'There was some error']);
                }
            }
        }

    }

}
