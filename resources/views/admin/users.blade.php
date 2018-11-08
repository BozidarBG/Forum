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

                            <th>Banned</th>
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
                                        <div class="dropdown-menu" data-id="{{$user->id}}">
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
                        <button class="btn btn-success" type="submit" name="submit" id="send" data-id="">Change</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>

        let token=$('input[name=_token]').val();
        let data={};
        data._token=token;

            //if we click ok, modal should appear to confirm if we want to take this action
            $('.confirm-modal').on('click', function (){
                let time=$(event.target).attr('data-time');
                let text;

                let name=$(event.target).parent().parent().parent().prev().prev().text();
                let id=$(event.target).parent().attr('data-id');
                if(time==10){
                    text="Are you sure that you want to ban "+name+" forever?";
                }else if(time==0){
                    text="Are you sure that you want to  remove ban for "+name+"?";
                }else{
                    text="Are you sure that you want to ban "+name+" for "+time+" days?";
                }
                $('#confirmModal').modal('show');
                $('#confirmModal .modal-header span').text(text);
                //if we click send, we will take id of that user and ban time and send it on server
                data.time=time;
                data.id=id;
                $('#send').on('click', function(){
                    $.ajax({
                        url:"/admin-ban",
                        method:'post',
                        data:data,
                        dataType:'json',
                        success:function(data){
                            console.log(data)
                            if(data.success){
                                toastr.success(data.success);
                                $('#confirmModal').modal('hide');
                                setTimeout(function(){
                                    location.reload();
                                }, 1500);


                            }else{
                                toastr.success(data.error);
                                $('#confirmModal').modal('hide');
                            }
                        }
                    });

                });

            });


//        });//end click ban user

    </script>
@endsection