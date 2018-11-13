@extends('layouts.frontend')
@section('styles')
    <style type="text/css">
        .nounderline, .violet{
            color: #7c4dff !important;
        }
        .btn-dark {
            background-color: #7c4dff !important;
            border-color: #7c4dff !important;
        }
        .btn-dark .file-upload {
            width: 100%;
            padding: 10px 0px;
            position: absolute;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .profile-img img{
            width: 200px;
            height: 200px;

        }
    </style>
@endsection
@section('content')
    <div class="col-md-10 mt-5">
        <div class="card">
            <div class="card-header">
                <h3>{{$user->name}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img p-3 text-center">
                            <p>&nbsp;</p>
                            <img src="{{asset($user->getAvatar())}}">
                            <p class="mt-3">Member since {{$user->profile->showCreated()}}</p>
                        </div>

                        @auth
                        @if(Auth::id() == $user->id)
                        <a href="{{route('edit.profile')}}" class="btn btn-outline-primary">Edit profile</a>
                        @endif
                        @endauth
                    </div>
                    <div class="col-md-8">
                        <h5>About me:</h5>
                        {!! $user->profile->about ?? '<p class="font-weight-light font-italic">Empty</p>' !!}
                        <h6>Country/City</h6>
                        <p>{!! $user->profile->country->name ?? '<p class="font-weight-light font-italic">Empty</p>' !!}/{!! $user->profile->city ?? '<p class="font-weight-light font-italic">Empty</p>'!!}</p>
                        <h6>Contact Email</h6>
                        <p>{!! $user->profile->email ?? '<p class="font-weight-light font-italic">Empty</p>'!!}</p>
                        <h6>Web</h6>
                        <p>{!! $user->profile->web ?? '<p class="font-weight-light font-italic">Empty</p>' !!}</p>
                        <h6>Working as</h6>
                        <p>{!! $user->profile->job ?? '<p class="font-weight-light font-italic">Empty</p>' !!}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="card m-2 w-100">
                        <ul class="nav nav-tabs m-2" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Questions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Replies</a>
                            </li>

                        </ul>
                        <div class="tab-content m-2" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                @if($user->questions)
                                    @foreach($user->questions as $question)
                                        <p><a href="{{route('question.show',['slug'=>$question->slug])}}">{{$question->title}}</a></p>
                                        @endforeach
                                    @else
                                    <p>You don't have any questions</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                @if($user->replies)
                                    @foreach($user->replies as $reply)
                                        <p><a href="{{route('question.show',['slug'=>$reply->question->slug])}}">{!! $reply->shortenText($reply->content, 10) !!}</a></p>
                                    @endforeach
                                @else
                                    <p>You don't have any questions</p>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection