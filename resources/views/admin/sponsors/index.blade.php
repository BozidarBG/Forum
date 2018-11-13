@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h4>All Sponsors</h4>
                    </div>

                    <div class="float-right">
                        <a href="{{route('admin.sponsors.create')}}" class="btn btn-outline-info">Add new Sponsor</a>
                    </div>
                </div>
                <div class="card-body">
                @if($sponsors->count())
                @foreach($sponsors as $sponsor)
                    <div class="card" data-id="{{$sponsor->id}}">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{asset($sponsor->banner)}}" width="50%">
                            </div>
                            <div class="col-md-3">
                                <p class="mt-3">{{$sponsor->name}}</p>
                                <p>{{$sponsor->link}}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mt-3">{{$sponsor->position}}</p>
                            </div>
                            <div class="col-md-3">
                                <a href="{{route('admin.sponsors.edit', ['id'=>$sponsor->id])}}" class="mt-3 btn btn-warning edit">Edit</a>&nbsp;
                                <button class="mt-3 btn btn-danger delete" data-id="{{$sponsor->id}}">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                    <h6>There are no sponsors yet</h6>
                @endif
                </div>
            </div>
        </div>
        <!--confirm modal-->
        <div class="modal fade" id="confirmModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Are you sure that you want to delete this?</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" id="send" data-id="">Delete</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            let token="{!! csrf_token() !!}";
            let data={};
            data._token=token;

//if we click delete, modal should appear to confirm if we want to take this action
            $('.delete').on('click', function (){
                let id=$(event.target).attr('data-id');

                $('#confirmModal').modal('show');
                //if we click send, we will take id of that sponsor and send it to server to delete it
                data.id=id;
                $('#send').on('click', function(){
                    $.ajax({
                        url:"/admin-sponsors-delete",
                        method:'post',
                        data:data,
                        dataType:'json',
                        success:function(data){

                            if(data=="success"){
                                toastr.success('Sponsor deleted successfully!');
                                $('#confirmModal').modal('hide');
                                setTimeout(function(){
                                    location.reload();
                                }, 1500);


                            }else{
                                toastr.success('There was some error');
                                $('#confirmModal').modal('hide');
                            }
                        }
                    });

                });

            });

        });
    </script>
@endsection