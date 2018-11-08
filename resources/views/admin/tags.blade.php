@extends('layouts.admin')
@section('styles')
    <style>
        #tags, h3{
            font-size: 0.8rem;
        }
        .display_none{
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="card p-1" id="tags">

        {{--@include('success-error')--}}
        <div class="row">
            <div class="col-md-8">
                <div class="card" id="tag_card">
                    <div class="card-header">
                        <h3>Create Tag</h3>
                    </div>
                    <div class="card-body">
                        <form id="create" method="post">

                            <div class="form-group">
                                <input type="text" id="title" class="form-control" placeholder="Enter title" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="body" required id="text_body"></textarea>
                            </div>
                            <div class="form-group" id="card_button">
                                <button class="btn btn-block btn-primary">Create Tag</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    {!! csrf_field() !!}
                    <div class="card-header">
                        <h3>All Tags</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th class="tag_title">Title</th>

                                <th class="tag_btn">Edit</th>
                                <th class="tag_btn">Del</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($tags->count()>0)
                                @foreach($tags as $tag)
                                    <tr data-id="{{$tag->id}}">
                                        <td class="tag_title">{{$tag->title}}</td>
                                        <td class="display_none">{!! $tag->body !!}</td>
                                        <td class="tag_btn"><button class="btn btn-sm btn-info edit"><i class="far fa-edit"></i></button></td>
                                        <td class="tag_btn"><button class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td>There are no tags</td></tr>
                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'body' );
        CKEDITOR.config.removePlugins = 'image';
    </script>
    <script>
        let token=$('[name="_token"]').val();

        let editObj={};
        addEditAndDeleteListeners();

        //gives edit and delete listeners to all buttons with .edit and .delete
        function addEditAndDeleteListeners(){
            $('.edit').on('click', function(event){
                editTag(event);
            });

            $('.delete').on('click', function(event){
                deleteTag(event);
            });
        }

        //CREATE NEW TAG
        $('#create').on('submit', function(){
            event.preventDefault();
            let title=$('#title').val();
            let body=CKEDITOR.instances["text_body"].getData();;

            if(title.length>=1 && body.length>=1){
                let form_data={};
                form_data.title=title;
                form_data.body=body;
                form_data._token=token;
                $.ajax({
                    url:"/tags",
                    method:'post',
                    data:form_data,
                    dataType:'json',
                    success: function(data){
                        if(data.success){
                            toastr.success(data.success)
                            let row=`<tr data-id="${data.id}">
                                    <td class="tag_title">${title}</td>
                                    <td class="display_none">${body}</td>
                                    <td class="tag_btn"><button class="btn btn-sm btn-info edit"><i class="far fa-edit"></i></button></td>
                                    <td class="tag_btn"><button class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></button></td>
                                    </tr>`;
                            $('tbody').append(row);
                            $('#title').val('');
                            CKEDITOR.instances["text_body"].setData('');
                            addEditAndDeleteListeners();
                        };
                    }

                });
            }
        });
        //END CREATE NEW SUBJECT


        //EDIT TAG
        //when we click Edit,
        // - change Create tag to Edit tag
        // - we are putting values from that tag into form.
        // - remove Create tag button and add Save Tag and Cancel
        // add to editObj id, title and body so that it can be compared with new values
        // Save tag will read fields and send them to backend
        // Cancel will empty form, changde back Edit tag to Create, remove Cancel and Save tag buttons
        // add back Create tag button
        function editTag(event) {
            event.preventDefault();
            //removes click listener for edit button
            //$(event.target).off('click');
            $('#tag_card').find('h3').text('Edit tag');
            let tableRow = $(event.target).closest('tr');

            let id = tableRow.attr('data-id');
            let title=tableRow.find('.tag_title').text();
            let body=tableRow.find('.display_none').html();
            //populate form with values for this tag
            $('#title').val(title);
            CKEDITOR.instances["text_body"].setData(body);
            $('#card_button').html('');
            let saveBtn="<button class='btn btn-success btn-block save'>Save</button>";
            let cancelBtn="<button class='btn btn-warning btn-block cancel'>Cancel</button>";
            $('#card_button').append(saveBtn);
            $('#card_button').append(cancelBtn);
            editObj.id=id;
            editObj.title=title;
            editObj.body=body;
            //add event listeners to new buttons
            $('.cancel').on('click', function (event) {
                returnToCreate()
            });
            $('.save').on('click', function(event){
                saveDataByAjax(event, id);
            })
        }
        //if we click cancel, we will change h3 title back to Create tag, remove cancel and save buttons and
        //show Create tag button. also, clear title and body of the form
        function returnToCreate() {
            $('#tag_card').find('h3').text('Create tag');
            $('#card_button').html('');
            let createBtn='<button class="btn btn-block btn-primary">Create Tag</button>';
            $('#card_button').append(createBtn);
            $('#title').val('');
            CKEDITOR.instances["text_body"].setData('');
        }

        //if we click save, we are sending ajax request
        function saveDataByAjax(event, id){
            event.preventDefault();
            //check if new value is different from old value
            let newTitle=$('#title').val().trim();
            let newBody=CKEDITOR.instances["text_body"].getData().trim();

            if(editObj.title == newTitle && editObj.body ==newBody){
                //new title and new body are the same and we won't send ajax request
                alert('New title and/or new body are the same')
            }else{
                //titles and bodies are different and we send ajax
                let form_data={};
                form_data.id=id;
                form_data.title=newTitle;
                form_data.body=newBody;
                form_data._token=token;

                $.ajax({
                    url:"/tags-update",
                    method:'post',
                    data:form_data,
                    dataType:'json',
                    success: function(data){
                        //console.log(data);
                        if(data.success){
                            toastr.success(data.success)
                            //update value in the row and bring back cancel and save
                            //
                            $('tr').each(function(){
                                if($(this).attr('data-id')==data.id){
                                    $(this).find('.tag_title').text(newTitle);
                                    $(this).find('.display_none').html(newBody);
                                }
                            });
                            returnToCreate();
                            //give everyone edit and delete listeners
                            addEditAndDeleteListeners();
                        }
                    }

                });
            }
        }

        //END EDIT SUBJECT

        //DELETE SUBJECT
        function deleteTag(event){
            event.preventDefault();
            let rowToDelete = $(event.target).closest('tr');
            let id=rowToDelete.attr('data-id');
            if(id){
                let form_data="id="+id+"&_token="+token;
                $.ajax({
                    url:"/tags-destroy",
                    method:'post',
                    data:form_data,
                    dataType:'json',
                    success: function(data){
                        if(data.success){
                            //remove row
                            toastr.success(data.success)
                            rowToDelete.css('background', 'red');
                            rowToDelete.fadeOut(2000);
                        }
                    }

                });
            }
        }
        //END DELETE SUBJECT
    </script>
@endsection

