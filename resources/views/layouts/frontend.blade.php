<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Forum</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
    <!-- Custom styles for this template -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    @yield('styles')
</head>

<body>
<div class="container-fluid mb-0">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">{{ config('app.name', 'Forum') }}</a>

        <form class="form-inline my-2 my-lg-0" id="search_form" action="{{route('search')}}" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Search" id="search_field" name="title">
            </div>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbar">
            <ul class="navbar-nav ">
                @guest
                <li class="nav-item active">
                    <a class="nav-link start-login-modal" id="login-modal" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link start-register-modal" id="register-modal" href="#">Register</a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="{{route('my.profile')}}">Go to profile</a>
                        @if(Auth::user()->isAdmin())

                        <a class="dropdown-item" href="{{route('admin.dashboard')}}">Admin dashboard</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </nav>
    <div class="row mt-5 mb-5">
<!-- left nav -->
        <div class="col-md-2 col-sm-12 mt-5 left-column">
            <div class="list-group">
                <a class="list-group-item {{Route::current()->uri === '/' || Route::current()->uri === 'most-liked' || Route::current()->uri === 'most-replied' ? 'active' : ''}}" href="{{route('home')}}">Home</a>
                <a class="list-group-item {{Route::current()->uri === 'all-tags'  ? 'active' : ''}}" href="{{route('all.tags')}}">Tags</a>
                <a class="list-group-item {{Route::current()->uri === 'users' ? 'active' : ''}}" href="{{route('all.users')}}">Users</a>
                @auth
                <a class="list-group-item {{Route::current()->uri === 'my-profile' || Route::current()->uri === 'edit-profile' ? 'active' : ''}}" href="{{route('my.profile')}}">My profile</a>
                @endauth
            </div>
        </div>
@yield('content')
    </div>

</div>
<footer class="container-fluid bg-dark mb-0 pb-0">
    <p>&copy; Company 2017-2018</p>
    <p class="p-2">ovde su neka sranja</p>
</footer>



<!--register modal start-->
<div class="modal fade" id="registerModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="register_form">
                <div class="modal-header">
                    <h4 class="modal-title">Please register</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <div id="register_form_output"></div>
                    <div class="form-group">
                        <label>Enter your name</label>
                        <input type="text" name="name" class="form-control" id="register_name">
                    </div>
                    <div class="form-group">
                        <label>Enter your email</label>
                        <input type="email" name="email" class="form-control" id="register_email">
                    </div>
                    <div class="form-group">
                        <label>Enter your password (minimum 6 characters)</label>
                        <input type="password" name="password" class="form-control" id="register_password">
                    </div>
                    <div class="form-group">
                        <label>Re-type your password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="register_confirm">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" id="register" value="Register" class="btn btn-info">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- register modal end -->
<!--login modal start-->
<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="login_form">
                <div class="modal-header">
                    <h4 class="modal-title">Please enter your login info</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body" id="login-body">
                    {{csrf_field()}}
                    <div id="login_form_output"></div>
                    <div class="form-group">
                        <label>Enter your email</label>
                        <input type="email" name="email" class="form-control" id="login_email" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label>Enter your password</label>
                        <input type="password" name="password" class="form-control" id="login_password">
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>

                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" id="login" value="Login" class="btn btn-info">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- login modal end -->

<!-- Bootstrap core JavaScript
================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{{--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script>
    @if(Session::has('success'))
        toastr.options.hideMethod = 'slideUp';
        toastr.options.closeButton = true;
    toastr.success("{{Session::get('success')}}");
    @endif


    @if(Session::has('error'))
        toastr.options.hideMethod = 'slideUp';
        toastr.options.closeButton = true;
    toastr.error("{{Session::get('error')}}");
    @endif
</script>
<script>

    $(document).ready(function(){

        //open register modal
        $('.start-register-modal').click(function(){
            //click register button to show register modal
            $('#registerModal').modal('show');
            //reset all fields in the form
            $('#register_form')[0].reset();
            //delete all errors
            $('#register_form_output').html('');
            });//end open modal


        //sending register
        $('#register_form').on('submit', function(event) {
            event.preventDefault();
            let name = $('#register_name').val().length > 2;
            let email = $('#register_email').val();
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            let isAnEmail = re.test(email.toLowerCase());
            let passwordLength = $('#register_password').val().length >= 6;
            let passwordConfirm = $('#register_password').val() == $('#register_confirm').val();

            if(name && isAnEmail && passwordLength && passwordConfirm) {
                //converts data to string
                var form_data = $(this).serialize();
                //send ajax request
                $.ajax({
                    url: "{{config('app.url').'/register'}}",
                    method: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (data) {

                        if (data[0] == "error") {
                            //we have errors
                            let rows = "";
                            $.each(data[1], function (index, message) {
                                rows += "<div class='alert alert-danger'>" + message + "</div>";
                            })

                            $('#register_form_output').append(rows);
                        } else if (data == "success") {
                            $('#register_form')[0].reset();
                            $('#registerModal').modal('hide');
                            location.reload();
                        }
                    }

                });//end .ajax
            }else{
                    $('#register_form_output').html('')
                    //some inputs are not correct
                if(!name){
                    let row="<div class='alert alert-danger'>Your name is not minimum 3 characters long</div>";
                    $('#register_form_output').append(row);
                }
                if(!isAnEmail){
                    let row="<div class='alert alert-danger'>Email is not in correct format</div>";
                    $('#register_form_output').append(row);
                }
                if(!passwordLength){
                    let row="<div class='alert alert-danger'>Password must be at least 6 characters long</div>";
                    $('#register_form_output').append(row);
                }
                if(!passwordConfirm){
                    let row="<div class='alert alert-danger'>Your password and retyped password don't match</div>";
                    $('#register_form_output').append(row);
                }
            }
        });//end send register form

        //open login modal
        $('.start-login-modal').click(function(){
            //when we click Login button, modal shows up
            $('#loginModal').modal('show');
            //reset all values in modal
            $('#login_form')[0].reset();
            //delete spans (if any)
            $('#login_form_output').html('');

        });//end open login modal


        //sending login
        $('#login_form').on('submit', function(event){
            event.preventDefault();
            //convert data to string and sends to server
            let email=$('#login_email').val();
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            let isAnEmail = re.test(email.toLowerCase());
            let passwordLength=$('#login_password').val().length >=6;
            let remember=$('#remember').prop('checked');
            if(isAnEmail && passwordLength){
                let form_data=$(this).serialize();
                $.ajax({
                    url:"{{config('app.url').'/login'}}",
                    method: 'POST',
                    data: form_data,
                    dataType:'json',

                    success: function(data){
                        if(data=="success"){
                            $('#loginModal').modal('hide');
                            location.reload();
                        }else{
                            let row="<div class='alert alert-danger'>There was an error with your credentials</div>";
                            $('#login_form_output').append(row);
                        }

                    }
                });
            }else{
                $('#login_form_output').html('')
                //password and/or email are not correct
                if(!isAnEmail){
                    let row="<div class='alert alert-danger'>Email is not in correct format</div>";
                    $('#login_form_output').append(row);
                }
                if(!passwordLength){
                    let row="<div class='alert alert-danger'>Password must be at least 6 characters long</div>";
                    $('#login_form_output').append(row);
                }
            }

        });//end send ajax login

    $('#search_form').submit(function(){
        event.preventDefault();
        let title=$('#search_field').val().trim();

        if(title !=""){
            $(this).unbind('submit').submit()
        }
    });

    });//end document ready

</script>

@yield('scripts')
</body>
</html>
