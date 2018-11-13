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
<div class="card mb-3">
    <div class="card-header">
        <h6>All questions</h6>
        {!! csrf_field() !!}
    </div>
    <div class="m-1 row table">
        <div class="col-md-1 text-center">#</div>
        <div class="col-md-7">Title</div>
        <div class="col-md-3">Name</div>
        <div class="col-md-1">Del</div>
    </div>
        @foreach($questions as $question)
    <div class="row results" data-id="{{$question->id}}">
        <div class="col-md-1 text-center id" >{{$question->id}}</div>
        <div class="col-md-7 change text-primary">{{$question->title}}</div>
        <div class="col-md-3">{{$question->user->name}}</div>
        <div class="col-md-1 pb-2"><button class="btn btn-sm btn-danger delete">Del</button></div>
        <div class="col-md-12 pl-5 content display">{!! $question->content !!}</div>
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
<div class="text-center">
    {!! $questions->links() !!}
</div>
    @endsection
@section('scripts')
<script>
    //when we click titke, we will show/hide content of the question
    $('.change').on('click', function(){
        $(this).next().next().next().toggle('.display');
    });

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
                    url:'/delete-question/'+id,
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