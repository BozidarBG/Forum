@extends('layouts.frontend')
@section('styles')
<style>
    .card-header a{
        color:#ffffff;
    }
    .card-header a:hover{
        color:#4e555b;
        text-decoration: none;
    }

</style>
@endsection
@section('content')

    <div class="col-md-10 mt-5">

        <div class="card border-info">
            <div class="card-header bg-info">
                <a href="{{route('show.user', ['hashid'=>$question->user->hashid])}}"><img class="mr-3" src="{{asset($question->user->getAvatar())}}" alt="user-image" width="50px"></a>
                <a href="{{route('show.user', ['hashid'=>$question->user->hashid])}}">{{$question->user->name}}</a> has asked:
                <div class="float-right">
                    {!! csrf_field() !!}
                    @auth
                    <button class="btn btn-warning start-report-modal" data-hashid="{{$question->user->hashid}}">Report User</button>
                    @endauth
                    <input type="hidden" id="question_id" value="{{$question->id}}">
                    @if($question->is_liked_by_auth_user())
                        <button class="btn btn-danger like">Unlike <span class="badge badge-light">{{$question->likes->count()}}</span></button>
                    @else
                        <button class="btn btn-success like">Like <span class="badge badge-light">{{$question->likes->count()}}</span></button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h3>{{$question->title}}</h3>
                {!! $question->content !!}
            </div>
        </div>
        @if($question->replies->count()>0)
        <h4 class=" m-2">
            Replies:
        </h4>
        @foreach($question->replies as $reply)
        <div class="card-body ">
            <div class="card border-warning m-1">
                <div class="card-header bg-warning">
                    <a href="{{route('show.user', ['hashid'=>$reply->user->hashid])}}"><img class="mr-3" src="{{asset($reply->user->getAvatar())}}" alt="user-image" width="50px"></a>
                    <a href="{{route('show.user', ['hashid'=>$reply->user->hashid])}}">{{$reply->user->name}}</a> has replied:
                    <div class="float-right">
                        {!! csrf_field() !!}
                        @auth
                        <button class="btn btn-primary start-report-modal" data-hashid="{{$reply->user->hashid}}">Report User</button>
                        @endauth
                        @if($reply->is_liked_by_auth_user())
                            <button class="btn btn-danger likeReply" data-id="{{$reply->id}}">Unlike <span class="badge badge-light">{{$reply->likes->count()}}</span></button>
                        @else
                            <button class="btn btn-success likeReply" data-id="{{$reply->id}}">Like <span class="badge badge-light">{{$reply->likes->count()}}</span></button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {!! $reply->content !!}
                </div>
            </div>
        </div>
        @endforeach
        @else
            <h4 class=" m-2">
                There are no replies. Be the first to reply
            </h4>
        @endif
        @if(Auth::check() && !Auth::user()->banned)
        <div class="card border-warning m-5">
            <div class="card-header bg-warning">
                <h3>Reply</h3>
            </div>
            <div class="card-body">
                <form action="{{route('reply.store')}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="question_id" value="{{$question->id}}">
                    <div class="form-group">
                        <label for="body">Post a reply for this question</label>
                        <textarea name="body"  class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Reply" class="form-control btn btn-outline-primary">
                    </div>
                </form>
            </div>
        </div>
        @elseif(Auth::check() && Auth::user()->banned)
            <p class="mt-5 text-center">You can't reply because you are banned</p>
            @else
            <p class="mt-5 text-center">Please
            <a class="btn  btn-outline-primary start-login-modal" href="#">Login</a>
            or
            <a class="btn btn-outline-primary start-register-modal"  href="#">Register</a>
            to reply to this question
            </p>
        @endif
    </div>

    <!-- report user modal -->
    <div class="modal fade" id="reportModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="report_form" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Report this user</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body" id="report-body">
                        {{csrf_field()}}
                        <div id="report_form_output"></div>

                        <div class="form-group">
                            <label>Enter your message for the administrator</label>
                            <textarea name="content" id="complaint_content"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" type="submit" name="submit" id="send" >Send</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@auth
    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'body' );
        CKEDITOR.config.removePlugins = 'image';

        CKEDITOR.replace( 'content' );
        CKEDITOR.config.removePlugins = 'image';
    </script>
@endauth
    <script>

    let token=$('input[name=_token]').val();
    let data={};
    data._token=token;
    let hashid;

//like/unlike question
        $('.like').on('click', function(){
            let btn=event.target;
            let id=$('#question_id').val();
            $.ajax({
                url:'/question/like/'+id,
                method:'post',
                dataType:'json',
                data:data,
                success:function(data){

                    if(data[0]=="liked"){

                        //change button to unlike and give it new count
                        let html="Unlike <span class='badge badge-light'>"+data[1]+"</span>";
                        $(btn).removeClass('btn-success like').addClass('btn-danger').html(html);


                    }else if(data[0]=="unliked") {
                        //change button to like and give it new count
                        let html="Like <span class='badge badge-light'>"+data[1]+"</span>"
                        $(btn).removeClass('btn-danger unlike').addClass('btn-success like').html(html);
                    }else{
                        toastr.error('Ooops there was some error');
                    }
                }
            });
        });

    //like/unlike reply
    $('.likeReply').on('click', function(){
        let btn=event.target;
        let id=$(btn).attr('data-id');
        $.ajax({
            url:'/reply/like/'+id,
            method:'post',
            dataType:'json',
            data:data,
            success:function(data){

                if(data[0]=="liked"){

                    //change button to unlike and give it new count
                    let html="Unlike <span class='badge badge-light'>"+data[1]+"</span>";
                    $(btn).removeClass('btn-success like').addClass('btn-danger').html(html);

                }else if(data[0]=="unliked") {
                    //change button to like and give it new count
                    let html="Like <span class='badge badge-light'>"+data[1]+"</span>"
                    $(btn).removeClass('btn-danger unlike').addClass('btn-success like').html(html);
                }else{
                    toastr.error('Ooops there was some error');
                }
            }
        });
    });

    //open report modal
    $('.start-report-modal').click(function(){
        //when we click Report button, modal shows up
        $('#reportModal').modal('show');
        //reset all values in modal
        CKEDITOR.instances["complaint_content"].setData('');
        //delete spans (if any)
        $('#report_form_output').html('');
        hashid=$(event.target).attr('data-hashid');
    });//end open report modal


    //sending report user
    $('#report_form').on('submit', function(event){
        event.preventDefault();
        //read data which will be sent. we need link to this question, reported user's hashid and content of complaint
        let link=$(location).attr('href');
        let content=CKEDITOR.instances["complaint_content"].getData().trim()
        //let content=$('#complaint_content').val();

        if(link && hashid && content){
            let form_data={};
            form_data.link=link;
            form_data.hashid=hashid;
            form_data.body=content;
            form_data._token=token;

            $.ajax({
                url:"{{config('app.url').'/report-user'}}",
                method: 'post',
                data: form_data,
                dataType:'json',

                success: function(data){
                    if(data.success){
                        $('#reportModal').modal('hide');
                        toastr.success(data.success)
                    }else{
                        let row="<div class='alert alert-danger'>"+data.error+"</div>";
                        $('#report_form_output').append(row);
                    }

                }
            });
        }else{
            $('#report_form_output').html('')
            //content is empty

            if(!content){
                let row="<div class='alert alert-danger'>You need to write some content in order to complain</div>";
                $('#report_form_output').append(row);
            }
        }

    });//end send ajax complain

    </script>

@endsection