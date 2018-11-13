<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sponsor;
use Session;
use Log;
use File;

class SponsorController extends Controller
{
    public function index(){
        return view('admin.sponsors.index')->with('sponsors', Sponsor::all());
    }

    public function create(){
        return view('admin.sponsors.create')->with('page_name', 'create');
    }

    public function store(Request $request){
        //Log::info($request->all());
        $this->validate($request, [
            'position'=>'required|integer',
            'link'=>'required|string',
            'name'=>'required|string',
            'banner'=>'required|image'
        ]);

        $sponsor=new Sponsor();
        $sponsor->position=$request->position;
        $sponsor->link=$request->link;
        $sponsor->name=$request->name;

        $image = $request->file('banner');
        // Rename image
        $filename = time().$request->name. '.' . $image->guessExtension();
        $image->move('uploads/banners', $filename);

        $sponsor->banner = 'uploads/banners/'.$filename;
        if ($sponsor->save()) {

            return response()->json('success');
        } else {
            return response()->json('error');
        }

    }

    public function edit(Sponsor $sponsor){
        return view('admin.sponsors.create')->with('page_name', 'edit')->with('sponsor', $sponsor);
    }

    public function update(Request $request){
        $this->validate($request, [
            'id'=>'required|integer',
            'position'=>'integer',
            'link'=>'string',
            'name'=>'string',
            'banner'=>'image'
        ]);

        $sponsor=Sponsor::findOrFail($request->id);
        $sponsor->position=$request->position;
        $sponsor->link=$request->link;
        $sponsor->name=$request->name;
        if($request->has('banner')){
            $image = $request->file('banner');
            // Rename image
            $filename = time().$request->name. '.' . $image->guessExtension();
            $image->move('uploads/banners', $filename);
            $sponsor->banner = 'uploads/banners/'.$filename;
        }

        if ($sponsor->save()) {

            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function destroy(Request $request){
        $this->validate($request, ['id'=>'required']);

        $sponsor=Sponsor::findOrFail($request->id);

        if($sponsor->delete()){
            $path = parse_url($sponsor->banner);
            File::delete(public_path($path['path']));
            return response()->json('success');
        }else{
            return response()->json('error');
        }
    }

}
