<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Dashboard</title>

    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">

    @yield('styles')

</head>

<body id="page-top" class="sidebar-toggled">

<nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <ul class="navbar-nav ml-auto ml-md-0">

            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{Auth::user()->name}}
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{route('my.profile')}}">Profile</a>
                    <a class="dropdown-item" href="{{route('home')}}">Root</a>
                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{route('logout')}}">
                        {{csrf_field()}}
                        <button class="dropdown-item">Logout</button>
                    </form>

                </div>
            </li>
        </ul>
    </div>
    <!-- Navbar -->
</nav>
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav toggled">
        <li class="nav-item {{Route::current()->uri == 'dashboard' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item {{Route::current()->uri === 'admin-users' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.users')}}">
                <i class="fas fa-users"></i>
                <span>Users</span></a>
        </li>

        <li class="nav-item {{Route::current()->uri === 'admin-questions' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.questions')}}">
                <i class="far fa-question-circle"></i>
                <span>Questions</span></a>
        </li>
        <li class="nav-item {{Route::current()->uri === 'admin-replies' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.replies')}}">
                <i class="far fa-comments"></i>
                <span>Replies</span></a>
        </li>

        <li class="nav-item {{Route::current()->uri === 'admin-complaints' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.complaints')}}">
                <i class="fas fa-user-slash"></i>
                <span>Complaints</span></a>
        </li>
        <li class="nav-item {{Route::current()->uri === 'admin-tags' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.tags')}}">
                <i class="fas fa-fw fa-table"></i>
                <span>Tags</span></a>
        </li>

    </ul>

    <div id="content-wrapper">
        <div class="container-fluid">

            @yield('content')

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<!-- Bootstrap core JavaScript-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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

<!-- Custom scripts for all pages-->
<script src="{{asset('js/sb-admin.js')}}"></script>



@yield('scripts')
</body>

</html>
