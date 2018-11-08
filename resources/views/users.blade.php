@extends('layouts.frontend')
@section('styles')

@endsection
@section('content')
    <div class="col-md-10 mt-5">
        All Users
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-4">
                    <div class="card my-1">
                        <div class="card-body">
                            <a href="{{route('show.user', ['hashid'=>$user->hashid])}}"><img src="{{asset($user->getAvatar())}}" width="100px"></a>
                            <p>
                                @if(Auth::check() && Auth::id()== $user->id)
                                    <a href="{{route('my.profile')}}">{{$user->name}}</a>
                                    @else
                                    <a href="{{route('show.user', ['hashid'=>$user->hashid])}}">{{$user->name}}</a>
                                @endif
                            </p>
                            <p>{!! $user->profile->shortenText($user->profile->about, 10) !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <ul class="pagination mt-3">
            {{$users->links()}}
        </ul>
    </div>

@endsection>