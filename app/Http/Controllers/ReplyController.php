<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Likereply;
use Illuminate\Http\Request;
use Auth;
use Session;

class ReplyController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'question_id' => 'required',
            'body' => 'required|string'
        ]);

        $reply = new Reply();
        $reply->user_id = Auth::id();
        $reply->question_id = $request->question_id;
        $reply->content = $request->body;
        if ($reply->save()) {
            Session::flash('success', 'Reply saved successfully!');

        } else {
            Session::flash('error', 'Something went wrong!');
        }
        return redirect()->back();
    }

    //method that does toggle like/unlike. if auth user has liked this question, then unlike it, if he hasn't liked it before,
    //then like it
    public function likeReply(Reply $reply)
    {
        if (!$reply->is_liked_by_auth_user()) {
            $like = new Likereply();
            $like->reply_id = $reply->id;
            $like->user_id = Auth::id();
            $like->timestamps = false;
            if ($like->save()) {
                $likeCount = $reply->likes->count() + 1;
                return response()->json(['liked', $likeCount]);
            } else {
                return response()->json(['error']);
            }
        } else {
            $like = Likereply::where('reply_id', $reply->id)->where('user_id', Auth::id())->first();
            if ($like->delete()) {
                $likeCount = $reply->likes->count() - 1;
                return response()->json(['unliked', $likeCount]);
            } else {
                return response()->json(['error']);
            }
        }
    }
}
