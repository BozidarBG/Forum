@extends('layouts.frontend')

@section('content')

    <div class="col-md-7 middle-column">

        <h4 class="text-center">Questions</h4>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
            <div class="navbar-nav">
                <a class="nav-item nav-link {{Route::current()->uri === '/' ? 'active' : ''}}" href="{{route('home')}}">Newest</a>
                <a class="nav-item nav-link {{Route::current()->uri === 'most-replied' ? 'active' : ''}}" href="{{route('most.replied')}}">Most replied</a>
                <a class="nav-item nav-link {{Route::current()->uri === 'most-liked' ? 'active' : ''}}" href="{{route('most.liked')}}">Most liked</a>
            </div>
        </nav>
        <!-- card/question start -->
        @foreach($questions as $question)
        <div class="card mb-4">
            <div class="card-header">
                <div class="media">
                    <img class="mr-3" src="{{$question->user->getAvatar()}}" alt="user-image">
                    <div class="media-body">
                        <a href="#" class="question-text mt-0 mb-1">
                            {{$question->user->name}} has asked:
                        </a>
                        <br>
                        <a href="{{route('question.show',['slug'=>$question->slug])}}" class="question-text mt-1 mb-1">
                            {{$question->title}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body text-center">
                <button type="button" class="btn btn-outline-primary mr-3" disabled>
                    Views <span class="badge badge-primary">{{$question->views}}</span>
                </button>
                <button type="button" class="btn btn-outline-success mr-3" disabled>
                    Likes <span class="badge badge-success">{{$question->likes->count()}}</span>
                </button>
                <button type="button" class="btn btn-outline-warning" disabled>
                    Replies <span class="badge badge-warning">{{$question->replies->count()}}</span>
                </button>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">{{$question->created_at->diffForHumans()}}</small>
                @foreach($question->tags as $tag)
                <a class="btn btn-info btn-sm" href="{{route('tags', ['slug'=>$tag->slug])}}">{{$tag->title}}</a>
                @endforeach

            </div>
        </div>
        @endforeach
                {!! $questions->links() !!}
        <!-- card/question end -->

    </div>
    <div class="col-md-3 right-column">
        <div class="card">
            <h5 class="card-title text-center mt-3">Ask a question</h5>
            <img class="question-image" src="{{asset('images/question.png')}}" alt="Question">
            <div class="card-body">
                <h5 class="card-title">But before you ask:</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">- please see if this question was asked before</li>
                <li class="list-group-item">- please note that we don't tolerate foul language</li>
            </ul>
            <div class="card-body">
                @auth
                <a href="{{route('question.create')}}" class="btn btn-block btn-primary">Ask a question</a>
                @else
                    <a class="btn btn-block btn-primary start-login-modal" href="#">Login</a>
                <div class="text-center">or</div>
                    <a class="btn btn-block btn-primary start-register-modal"  href="#">Register</a>
                <div class="text-center">to ask a question</div>
                @endauth
            </div>
        </div>
    </div>

@endsection
