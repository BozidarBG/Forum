<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Like;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Complaint;
use App\User;
use Log;

class QuestionController extends Controller
{

    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tags'=>'required',
            'title'=>'string|required',
            'body'=>'string|required'
        ]);

        $question=new Question();
        $question->user_id=Auth::id();
        $question->title=$request->title;
        $question->content=$request->body;


        $question->save();
        for($i=0; $i<count($request->tags); $i++){
            $question->tags()->attach($request->tags[$i]);
        }
        Session::flash('success', 'Question saved successfully!');
        return redirect()->route('home');
    }

    //method that does toggle like/unlike. if auth user has liked this question, then unlike it, if he hasn't liked it before,
    //then like it
    public function likeQuestion(Question $question){
        if(!$question->is_liked_by_auth_user()){
            $like= new Like();
            $like->question_id=$question->id;
            $like->user_id=Auth::id();
            if($like->save()){
                $likeCount=$question->likes->count()+1;
                return response()->json(['liked', $likeCount]);
            }else{
                return response()->json(['error']);
            }
        }else{
            $like=Like::where('question_id', $question->id)->where('user_id', Auth::id())->first();
            if($like->delete()){
                $likeCount=$question->likes->count()-1;
                return response()->json(['unliked', $likeCount]);
            }else{
                return response()->json(['error']);
            }
        }
    }

//report user. users can report other user and admin will receive complaint
    public function reportUser(Request $request){
        //Log::info($request->all());
        $result=Validator::make($request->all(), [
            'hashid' => 'required|string',
            'link'=>'required|string',
            'body'=>'required|string'
        ]);

        $user=User::where('hashid', $request->hashid)->first();
        if(!$user){
            return response()->json(['error'=>'Error on the server']);
        }


        if(!$result->fails()){
            //create new row
            $complaint=new Complaint();
            $complaint->complained_to=$user->id;
            $complaint->body=$request->body;
            $complaint->complained_by=Auth::id();
            $complaint->link=$request->link;
            if ($complaint->save()) {
                return response()->json(['success'=>'This user has been reported!']);
            } else {
                return response()->json(['error'=>$complaint->errors()->all()]);
            }

        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }


}
