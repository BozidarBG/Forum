@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h4>{{$page_name =="create" ? 'Add Sponsor' : 'Edit Sponsor'}}</h4>
                    </div>
                    <div class="float-right">
                        <a href="{{route('admin.sponsors')}}" class="btn btn-outline-info">Back to all Sponsor</a>
                    </div>
                </div>
                <div class="card-body">
                    <form >
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$page_name=="edit" ? $sponsor->name : ''}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="banner">Banner</label><br>
                                    <input type="file" class="file-upload form-control" id="file-upload"
                                           name="banner" accept="image/*">
                                    @if($page_name=="edit")
                                        <img src="{{asset($sponsor->banner)}}" width="50px">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="text" name="link" id="link" class="form-control" value="{{$page_name=="edit" ? $sponsor->link : ''}}"  required>
                                </div>
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="number" name="position" id="position" class="form-control" value="{{$page_name=="edit" ? $sponsor->position : ''}}" required>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-outline-primary" id="save">
                                        @if($page_name=="create")
                                            Create
                                        @else
                                            <input type="hidden" id="id" value="{{ $sponsor->id }}">
                                            Update
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- The Modal -->
                    <div class="modal" id="myModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Crop Image And Upload</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div id="resizer"></div>
                                    <button class="btn rotate float-lef" data-deg="90" >
                                        <i class="fas fa-undo"></i></button>
                                    <button class="btn rotate float-right" data-deg="-90" >
                                        <i class="fas fa-redo"></i></button>
                                    <hr>
                                    <button class="btn btn-block btn-dark" id="upload" >
                                        Crop</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
    @if($page_name=="create")
    <script src="{{asset('js/sponsor-create.js')}}"></script>
    @elseif($page_name=="edit")
    <script src="{{asset('js/sponsor-edit.js')}}"></script>
    @endif


@endsection