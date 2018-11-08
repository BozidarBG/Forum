@extends('layouts.frontend')
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
    <div class="col-md-10 mt-5">
        <div class="card">
            <div class="card-header">
                <h3>{{$user->name}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p>&nbsp;</p>
                        <h5 class="card-title text-center">Change Avatar</h5>
                        <div class="profile-img p-3 text-center">
                            <img src="{{asset($user->getAvatar())}}" id="profile-pic">

                         </div>
                        <div class="text-center">
                            <div class="btn btn-dark">
                                <input type="file" class="file-upload" id="file-upload"
                                       name="profile_picture" accept="image/*">
                                Upload New Photo
                            </div>
                        </div>
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
                                                Crop And Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                    </div>
                    <div class="col-md-8">
                        <form action="{{route('update.profile')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="about">About you</label>
                                <textarea class="form-control" name="about">{{$user->profile->about}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="country">Select your country</label>
                                <select name="country_id" class="form-control">

                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{$country->id == $user->profile->country_id ? 'selected': '' }}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" name="city" class="form-control" value="{{$user->profile->city}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{$user->profile->email}}">
                            </div>
                            <div class="form-group">
                                <label for="web">Web address</label>
                                <input type="text" name="web" class="form-control" value="{{$user->profile->web}}">
                            </div>
                            <div class="form-group">
                                <label for="job">Your Job</label>
                                <input type="text" name="job" class="form-control" value="{{$user->profile->job}}">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" name="submit" value="Save changes to your profile">
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'about' );
        CKEDITOR.config.removePlugins = 'image';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
    <script>
        $(function() {
            var croppie = null;
            var el = document.getElementById('resizer');

            $.base64ImageToBlob = function(str) {
                // extract content type and base64 payload from original string
                var pos = str.indexOf(';base64,');
                var type = str.substring(5, pos);
                var b64 = str.substr(pos + 8);

                // decode base64
                var imageContent = atob(b64);

                // create an ArrayBuffer and a view (as unsigned 8-bit)
                var buffer = new ArrayBuffer(imageContent.length);
                var view = new Uint8Array(buffer);

                // fill the view, using the decoded base64
                for (var n = 0; n < imageContent.length; n++) {
                    view[n] = imageContent.charCodeAt(n);
                }

                // convert ArrayBuffer to Blob
                var blob = new Blob([buffer], { type: type });

                return blob;
            }

            $.getImage = function(input, croppie) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        croppie.bind({
                            url: e.target.result,
                        });
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#file-upload").on("change", function(event) {
                $("#myModal").modal();
                // Initailize croppie instance and assign it to global variable
                croppie = new Croppie(el, {
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 250,
                        height: 250
                    },
                    enableOrientation: true
                });
                $.getImage(event.target, croppie);
            });

            $("#upload").on("click", function() {
                croppie.result('base64').then(function(base64) {
                    $("#myModal").modal("hide");
                    $("#profile-pic").attr("src","/images/no-user.png");

                    var url = "{{ url('/update-avatar') }}";
                    var formData = new FormData();
                    formData.append("avatar", $.base64ImageToBlob(base64));

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data == "uploaded") {
                                $("#profile-pic").attr("src", base64);
                                toastr.success('Avatar changed successfully!')
                            } else {
                                $("#profile-pic").attr("src","/images/no-user.png");
                                toastr.error('There was some error on the server');
                                console.log(data['profile_picture']);
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            $("#profile-pic").attr("src","/images/no-user.png");
                        }
                    });
                });
            });

            // To Rotate Image Left or Right
            $(".rotate").on("click", function() {
                croppie.rotate(parseInt($(this).data('deg')));
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                // This function will call immediately after model close
                // To ensure that old croppie instance is destroyed on every model close
                setTimeout(function() { croppie.destroy(); }, 100);
            })

        });

    </script>
@endsection