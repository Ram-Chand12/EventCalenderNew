<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/assets/dist/css/style.css">
    <link rel="stylesheet" href="/assets/dist/css/style2.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">




    <!-- Your other HTML and scripts go here -->



</head>

<body class="hold-transition sidebar-mini">

    @include('sweetalert::alert')

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light px-3">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item top_nav_hamburger">
                    <a class="nav-link p-1" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="breadcrumb-item ml-2 my-auto "><a class="text-decoration-none text-dark"
                        href="/">Home</a></li>
                {{-- <li class="breadcrumb my-auto">{{ preg_replace('#^https?://[^/]+#', '', URL::current()) }}</li> --}}
                
                <li class="breadcrumb my-auto">
                    {{ preg_replace('#/\d+$#', '', preg_replace('#^https?://[^/]+#', '', URL::current())) }}
                </li>
                
                {{-- //new  --}}
                {{-- <div class="col-sm-6">
                    <ol class="breadcrumb mt-4">
                        <li class="breadcrumb mr-1">
                            <a data-widget="pushmenu" href="#" role="button" style="color: #00263e"><i
                                    class="fas fa-bars"></i></a>
                        </li>
                        <li class="breadcrumb-item mx-1 "><a href="/">Home</a></li>
                        <li class="breadcrumb ">{{preg_replace('#^https?://[^/]+#', '',URL::current())}}</li>
                    </ol>
                </div> --}}



                {{-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="/" class="nav-link">Dashboard</a>
                </li> --}}
                {{-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="/users" class="nav-link">Users</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/wordpress-users" class="nav-link">Wordpress Users</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/golf-groups" class="nav-link">Golf groups</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/golf-club" class="nav-link">Golf clubs</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/vanue" class="nav-link">Venues</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/organizer" class="nav-link">Organizers</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/category" class="nav-link">Categories</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/event" class="nav-link">Events</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/logs" class="nav-link">logs</a>
                </li> --}}
            </ul>
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    {{-- <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a> --}}
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/assets/dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/assets/dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/assets/dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li> --}}
                <!-- Notifications Dropdown Menu -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> --}}
                {{-- <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li> --}}
           
                <li class="nav-item pr-4">
                    {{-- <a class="nav-link" data-widget="fullscreen" href="#" role="button"> --}}
                        
                    <div class="user-panel d-flex ">
                        
                        <div class="info">
                            <span class="d-block"> {{ auth()->user()->name }}</span>
                        </div>
                        <div class="image pl-1">
                            <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                                alt="User Image">
                        </div>
                    </div>
                    {{-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"
                        role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        {{-- <i class="fa fa-solid fa-cross aside_nave_close"></i> --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4 custom-side-nav custom-side-nav-bar">

            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <img src="/assets/dist/img/Arcis-Golf-Side-By-Side.png" alt="AdminLTE Logo" class=""
                    style=" width: 150px;
                     background:#f4f6f9;">
                {{-- <span class="brand-text font-weight-light">
                    ARCIS GOLF</span> --}}
            </a>

            <!-- Sidebar -->
            <div class="sidebar custom-sidebar">
                <!-- Sidebar user panel (optional) -->
                {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex border-bottom-0"> --}}
                {{-- <div class="image">
                        <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"> {{ auth()->user()->name }}</a>
                    </div> --}}
                {{-- </div> --}}

                <!-- SidebarSearch Form -->
                {{-- <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        {{-- <li class="nav-item">
                            <a href="/" class="nav-link">
                                <i class="nav-icon fa-solid fa-gauge-high"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('wordpress_user') }}" class="nav-link">
                                <i class="nav-icon fa-brands fa-wordpress"></i>
                                <p>
                                    Authors
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('golf-group') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-people-group"></i>
                                <p>
                                    Golf Groups
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('golf-club') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-box"></i>
                                <p>
                                    Golf Clubs
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vanue') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-location-dot"></i>
                                <p>
                                    Venues
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('organizer') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-table-columns"></i>
                                <p>
                                    Organizers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-list"></i>
                                <p>
                                    Categories
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-calendar-days"></i>
                                <p>
                                    Events
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('log') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-file-lines"></i>
                                <p>
                                    Logs
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link log-out">
                                <i class="nav-icon fa-solid fa-power-off"></i>
                                <p class="m-0">Logout</p>
                                <form action="/logout" method="POST" id="logging-out">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>



        @yield('content')

        <!-- Content Wrapper. Contains page content -->
        {{-- content here --}}
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        {{-- <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">

            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="">Arcis Golf</a></strong> All rights
            reserved.
        </footer> --}}
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    


    <script>
        $(function() {
            var url = window.location;
            // for single sidebar menu
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');

            // for sidebar menu and treeview
            $('ul.nav-treeview a').filter(function() {
                    return this.href == url;
                }).parentsUntil(".nav-sidebar > .nav-treeview")
                .css({
                    'display': 'block'
                })
                .addClass('menu-open').prev('a')
                .addClass('active');
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                responsive: true
            });

        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '#btn-delete', function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7367f0',
                cancelButtonColor: '#82868b',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    </script>

    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <script>
        $(".log-out").on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You really want to log out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7367f0',
                cancelButtonColor: '#82868b',
                confirmButtonText: 'Yes, Log Out !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logging-out').submit()
                }
            })
        });
    </script>

    {{-- <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.0/ckeditor.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <!-- Include Bootstrap CSS -->

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Include Bootstrap Date Range Picker CSS -->


    <!-- Include Bootstrap Date Range Picker JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.js">
    </script>

    <script src="/assets/dist/js/custom.js"></script>
    @stack('scripts')
    <script src="/assets/dist/js/multiselect.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#undo_redo').multiselect();
        });
    </script>
</body>

</html>
