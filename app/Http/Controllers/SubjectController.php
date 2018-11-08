<?php

namespace App\Http\Controllers;

use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Session;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.subjects')->with('subjects', Subject::all());
    }

    public function store(Request $request)
    {
        //Log::info($request->all());
        $result=Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if(!$result->fails()){
            //create new row
            $subject=new Subject();
            $subject->title=$request->title;

            if ($subject->save()) {
                Session::flash('success', 'Subject created successfully!');
                return response()->json([
                    'success'=>'Subject created successfully!',
                    'id'=>$subject->id,
                    'title'=>$subject->title,
                    //'slug'=>$subject->slug
                ]);
            } else {
                return response()->json(['error'=>$subject->errors()->all()]);
            }

        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }



    public function update(Request $request, Subject $subject)
    {
        //Log::info($request->all());
        $result=Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|string|max:255',
        ]);

        if(!$result->fails()){
            //create new row
            $subject=Subject::find($request->id);
            $subject->title=$request->title;

            if ($subject->save()) {
                Session::flash('success', 'Subject updated successfully!');
                return response()->json([
                    'success'=>'Subject created successfully!',
                    'id'=>$subject->id,
                    'title'=>$subject->title,

                ]);
            } else {
                return response()->json(['error'=>$subject->errors()->all()]);
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
            $subject=Subject::find($request->id);
            $subject->delete();
            return response()->json([
                'success'=>'Subject deleted successfully!'
            ]);
        }else{
            return response()->json(['error', $result->errors()->all()]);
        }
    }
}
