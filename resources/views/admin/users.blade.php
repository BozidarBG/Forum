@extends('layouts.admin')
@section('styles')
    <style>
        .ok{
            visibility: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Users</h4>
                    {!! csrf_field() !!}
                </div>
                <div class="card-body">
                    <table class="table table-responsive-sm">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Registered</th>
                            <th>Action</th>

                            <th>Banned untill</th>
                        </tr>
                        </thead>

                        <tbody >
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class="name"><a href="{{route('show.user', ['hashid'=>$user->hashid])}}" target="_blank">{{$user->name}}</a></td>
                                <td>{{$user->showCreated()}}</td>
                                <td>
                                    <div class="btn-group dropright">
                                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Ban user
                                        </button>
                                        <div class="dropdown-menu" data-id="{{$user->id}}" data-name="{{$user->name}}">
                                            @if($user->banned)
                                                <a href="#" class="dropdown-item confirm-modal" data-time="0">Remove ban</a>
                                            @else
                                                <a href="#" class="dropdown-item confirm-modal" data-time="2">Ban for 2 days</a>
                                                <a href="#" class="dropdown-item confirm-modal" data-time="7">Ban for 7 days</a>
                                                {{--<a href="#" class="dropdown-item confirm-modal" data-time="10">Ban forever</a>--}}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{$user->banned ? Carbon\Carbon::parse($user->banned_until)->format('d.m.Y H:i:s') : 'No'}}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {!! $users->links() !!}
                    </div>
                </div>
            </div>
        </div>
        <!--confirm modal-->
        <div class="modal fade" id="confirmModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span></span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" name="submit" id="send">Change</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/ban-user.js')}}"></script>
@endsection