@extends('layouts.admin')
@section('styles')
    <style>
        .display{
            display: none;
        }
        .table div{
            margin-bottom: 10px;
            border-bottom: 1px solid #9BA2AB;
        }
        .results div:nth-child(2):hover{
            cursor: pointer;
        }

    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h6>All replies</h6>
            {!! csrf_field() !!}
        </div>
        <div class="m-1 row table">
            <div class="col-md-1 text-center">#</div>
            <div class="col-md-7">Content</div>
            <div class="col-md-3">Name</div>
            <div class="col-md-1">Del</div>
        </div>
        @foreach($replies as $reply)
            <div class="row results" data-id="{{$reply->id}}">
                <div class="col-md-1 text-center id" >{{$reply->id}}</div>
                <div class="col-md-7 change text-primary"><a target="_blank" href="{{route('question.show', ['hashid'=>$reply->question->slug])}}">{!! $reply->content !!}</a></div>
                <div class="col-md-3">{{$reply->user->name}}</div>
                <div class="col-md-1 pb-2"><button class="btn btn-sm btn-danger delete">Del</button></div>
            </div>

        @endforeach


        <div class="modal" tabindex="-1" role="dialog" id="confirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Please confirm</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure that you want to delete this?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger confirmed">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        //delete question
        $('.delete').on('click', function(){
            let rowToBeRemoved=$(this).parent().parent();
            let id=$(this).parent().parent().data('id');

            //when we click Del button, modal shows up
            $('#confirmDelete').modal('show');
            $('.confirmed').on('click', function(){
                //it is confirmed that we want to delete this row, so we send ajax request
                let data={};
                data._token=$('input[name=_token]').val();

                $.ajax({
                    url:'/delete-reply/'+id,
                    method: 'post',
                    dataType: 'json',
                    data:data,
                    success:function(data){
                        if(data.success){
                            $('#confirmDelete').modal('hide');
                            toastr.success(data.success)
                            //question was deleted and we need to remove that row
                            rowToBeRemoved.find('.change').css('background', 'red');
                            rowToBeRemoved.fadeOut(2000);
                        }else if(data.error){
                            $('.modal-body p').html(data.error);
                            $('.confirmed').remove();
                        }

                    }
                });
            });
        });
    </script>
@endsection