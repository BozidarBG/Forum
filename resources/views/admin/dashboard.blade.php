@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Latest users</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-25">#</th>
                            <th class="w-50">Name</th>
                            <th class="w-25">Profile</th>
                        </tr>
                    </thead>

                    <tbody >
                    @foreach($users as $user)
                        <tr>
                            <td class="w-25">{{$user->id}}</td>
                            <td class="w-50">{{$user->name}}</td>
                            <td class="w-25"><a href="{{route('show.user', ['hashid'=>$user->hashid])}}" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card">
                <div class="card-header">
                    <h4>Latest Questions</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="w-50">Title</th>
                            <th class="w-50">User</th>
                        </tr>
                        </thead>

                        <tbody >
                        @foreach($questions as $question)
                            <tr>
                                <td class="w-50"><a href="{{route('question.show', ['slug'=>$question->slug])}}">{{$question->title}}</a></td>
                                <td class="w-25"><a href="{{route('show.user', ['hashid'=>$question->user->hashid])}}">{{$question->user->name}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    @endsection