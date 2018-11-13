<?php

namespace App\Http\Controllers;

use App\Sponsor;
use App\Tag;
use App\User;
use App\Question;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class FrontendController extends Controller
{
    public function index(){

        return view('home')
            ->with('questions', Question::orderBy('created_at', 'desc')->paginate(10))
            ->with('sponsors', Sponsor::orderBy('position', 'asc')->get());
    }

    protected function paginate(Collection $collection){

        //page je current page a LengthAwarePaginator je iz Illuminate\Pagination i koristi metodu resolveCurrentPage
        $page=LengthAwarePaginator::resolveCurrentPage();

        $perPage=10;

        //od kog elementa slajsujemo i koliko komada
        //index počinje od nula... ako smo na prvoj stranici, page-1=0 * 0 =0 znači od 0 do 15-tog člana (bez rbr.15)
        //ako smo na drugoj 2-1*15 = od 15-tog člana do dalje
        $results=$collection->slice(($page-1)*$perPage, $perPage)->values();

        //rezultati, veličina kolekcije, koliko kom po stranici, trenutna stranica i opcije.
        //path nam pomaže da nadjemo sledeću i prethodnu stranicu
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);

        //moramo da kažemo da uključi i ostale parametre da ne bi ignorisao recimo story_by i ostale
        $paginated->appends(request()->all());
        return $paginated;
    }

    public function mostReplied(){

        $questions=Question::with('replies')->get();

        $sorted=$questions->sort(function($a, $b){
            return $a->countReplies() < $b->countReplies();
        });

        return view('home')
            ->with('questions', $this->paginate($sorted));

    }

    public function mostLiked(){

        $questions=Question::with('replies')->get();

        $sorted=$questions->sort(function($a, $b){
            return $a->countLikes() < $b->countLikes();
        });

        return view('home')
            ->with('questions', $this->paginate($sorted));
    }

    public function search(Request $request){
        \Log::info($request);
        $this->validate($request ,[
            'title'=>'required'
        ]);

        return view('home')
            ->with('questions', Question::where('title', 'like', '%'.$request->title.'%')
                ->orderBy('created_at', 'desc')->paginate(10));
    }

    public function showTag($slug){

        $tag=Tag::where('slug', $slug)->first() !==null ? Tag::where('slug', $slug)->first()->id : null;
        if($tag){
            return view('home')->with('questions',Tag::find($tag)->questions()->paginate(10));
        }else{
            return view('404');
        }

    }
//view one question
    public function question($slug){
        $question=Question::with('replies')->where('slug', $slug)->first();
        if(!$question){
            return redirect()->back();
        }
        $question->views = $question->views+1;
        $question->save();
        return view('question')->with('question', $question);
    }

    public function tags(){
        return view('tags')->with('tags', Tag::orderBy('title', 'asc')->paginate(12));
    }

    public function users(){
        return view('users')->with('users', User::orderBy('name', 'asc')->paginate(12));
    }


}
