<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Session;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tags')->with('tags', Tag::all());
    }

    public function store(Request $request)
    {
        //Log::info($request->all());
        $result=Validator::make($request->all(), [
            'title' => 'required|string',
            'body'=>'required|string'
        ]);

        if(!$result->fails()){
            //create new row
            $tag=new Tag();
            $tag->title=$request->title;
            $tag->body=$request->body;

            if ($tag->save()) {
                Session::flash('success', 'Tag created successfully!');
                return response()->json([
                    'success'=>'Tag created successfully!',
                    'id'=>$tag->id
                ]);
            } else {
                return response()->json(['error'=>$tag->errors()->all()]);
            }

        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }



    public function update(Request $request)
    {
        //Log::info($request->all());
        $result=Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|string',
            'body'=>'required|string'
        ]);

        if(!$result->fails()){

            $tag=Tag::find($request->id);
            $tag->title=$request->title;
            $tag->body=$request->body;
            if ($tag->save()) {
                return response()->json([
                    'success'=>'Tag updated successfully!',
                    'id'=>$tag->id
                ]);
            } else {
                return response()->json(['error'=>$tag->errors()->all()]);
            }

        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }


    public function destroy(Request $request)
    {
        $result=Validator::make($request->all(), [
            'id'=>'required',
        ]);

        if(!$result->fails()){
            $tag=Tag::find($request->id);
            $tag->delete();
            return response()->json([
                'success'=>'Tag deleted successfully!'
            ]);
        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }
}