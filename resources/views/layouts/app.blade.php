<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Light-bootstrap-dashboard-master --}}
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">    --}}
    
     <!-- Bootstrap core CSS     -->
     <link href="{{ asset('assets/finalTemplate/css/bootstrap.min.css')}}" rel="stylesheet" />
     <link href="{{ asset('assets/finalTemplate/css/light-bootstrap-dashboard.css')}}" rel="stylesheet" />
    
    <!--     Fonts and icons     -->
    <link href="{{ asset('assets/finalTemplate/fonts-googleapis.css')}}" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/finalTemplate/font-awesome.min.css')}}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free-5.11.2-web/css/all.css')}}">




    @yield('headScript')
</head>
<body>
    <div id="app" @yield('ng-app')>
        <div class="wrapper">
            <div class="sidebar" data-color="green" data-image="{{ asset('assets/finalTemplate/img/sidebar-5.jpg')}}">
                <!--
                    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
                    Tip 2: you can also add an image using data-image tag
                -->
                <div class="sidebar-wrapper">
                    <div class="logo">
                        <a href="http://www.creative-tim.com" class="simple-text">
                            Jael's Restaurant
                        </a>
                    </div>
                    <ul class="nav">
                        <li @yield('dashboard_link')>
                            <a class="nav-link" href="{{route('admin.dashboard')}}">
                                <i class="nc-icon nc-chart-pie-35"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li @yield('userProfile_link')>
                            <a class="nav-link" href="{{route('admin.userProfile')}}">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>User Profile</p>
                            </a>
                        </li>
                        <li @yield('categories_link')>
                            <a class="nav-link" href="{{route('admin.categories')}}">
                                <i class="nc-icon nc-notes"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li @yield('menus_link')>
                            <a class="nav-link" href="{{route('admin.menus')}}">
                                <i class="nc-icon nc-paper-2"></i>
                                <p>Menus</p>
                            </a>
                        </li>
                        <li @yield('orders_link')>
                            <a class="nav-link" href="{{route('admin.orders')}}">
                                <i class="nc-icon nc-paper-2"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                        <li @yield('tables_link')>
                            <a class="nav-link" href="{{route('admin.tables')}}">
                                <i class="nc-icon nc-paper-2"></i>
                                <p>Tables</p>
                            </a>
                        </li>
                        <li @yield('reports_link')>
                            <a class="nav-link" href="{{route('admin.reports')}}">
                                <i class="nc-icon nc-atom"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        <li @yield('settings_link')>
                            <a class="nav-link" href="{{route('admin.settings')}}">
                                <i class="nc-icon nc-pin-3"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li @yield('aboutUs_link')>
                            <a class="nav-link active" href="{{route('admin.aboutUs')}}">
                                <i class="nc-icon nc-alien-33"></i>
                                <p>About Us</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                        <div class="container-fluid">
                            {{-- <a class="navbar-brand" href="#pablo"> Dashboard </a> --}}
                            <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-bar burger-lines"></span>
                                <span class="navbar-toggler-bar burger-lines"></span>
                                <span class="navbar-toggler-bar burger-lines"></span>
                            </button>
                            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                                {{-- <ul class="nav navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-toggle="dropdown">
                                            <i class="nc-icon nc-palette"></i>
                                            <span class="d-lg-none">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="dropdown nav-item">
                                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                            <i class="nc-icon nc-planet"></i>
                                            <span class="notification">5</span>
                                            <span class="d-lg-none">Notification</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Notification 1</a>
                                            <a class="dropdown-item" href="#">Notification 2</a>
                                            <a class="dropdown-item" href="#">Notification 3</a>
                                            <a class="dropdown-item" href="#">Notification 4</a>
                                            <a class="dropdown-item" href="#">Another notification</a>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nc-icon nc-zoom-split"></i>
                                            <span class="d-lg-block">&nbsp;Search</span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#pablo">
                                            <span class="no-icon">Account</span>
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="no-icon">Dropdown</span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                            <div class="divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#pablo">
                                            <span class="no-icon">Log out</span>
                                        </a>
                                    </li>
                                </ul> --}}
                                <ul class="navbar-nav">
                                        <!-- Laravel Profile logout -->
                                            <!-- Authentication Links -->
                                            @guest
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                                </li>
                                                @if (Route::has('register'))
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                                    </li>
                                                @endif
                                                @else
                                                    <li class="nav-item dropdown">
                                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                            {{ Auth::user()->name }} <span class="caret"></span>
                                                        </a>
                        
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
                        </div>
                </nav>
                <!-- End Navbar -->
                    {{-- <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card ">
                                        <div class="card-header ">
                                            <h4 class="card-title">Email Statistics</h4>
                                            <p class="card-category">Last Campaign Performance</p>
                                        </div>
                                        <div class="card-body ">
                                            <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                                            <div class="legend">
                                                <i class="fa fa-circle text-info"></i> Open
                                                <i class="fa fa-circle text-danger"></i> Bounce
                                                <i class="fa fa-circle text-warning"></i> Unsubscribe
                                            </div>
                                            <hr>
                                            <div class="stats">
                                                <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card ">
                                        <div class="card-header ">
                                            <h4 class="card-title">Users Behavior</h4>
                                            <p class="card-category">24 Hours performance</p>
                                        </div>
                                        <div class="card-body ">
                                            <div id="chartHours" class="ct-chart"></div>
                                        </div>
                                        <div class="card-footer ">
                                            <div class="legend">
                                                <i class="fa fa-circle text-info"></i> Open
                                                <i class="fa fa-circle text-danger"></i> Click
                                                <i class="fa fa-circle text-warning"></i> Click Second Time
                                            </div>
                                            <hr>
                                            <div class="stats">
                                                <i class="fa fa-history"></i> Updated 3 minutes ago
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card ">
                                        <div class="card-header ">
                                            <h4 class="card-title">2017 Sales</h4>
                                            <p class="card-category">All products including Taxes</p>
                                        </div>
                                        <div class="card-body ">
                                            <div id="chartActivity" class="ct-chart"></div>
                                        </div>
                                        <div class="card-footer ">
                                            <div class="legend">
                                                <i class="fa fa-circle text-info"></i> Tesla Model S
                                                <i class="fa fa-circle text-danger"></i> BMW 5 Series
                                            </div>
                                            <hr>
                                            <div class="stats">
                                                <i class="fa fa-check"></i> Data information certified
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card  card-tasks">
                                        <div class="card-header ">
                                            <h4 class="card-title">Tasks</h4>
                                            <p class="card-category">Backend development</p>
                                        </div>
                                        <div class="card-body ">
                                            <div class="table-full-width">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" value="">
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Sign contract for "What are conference organizers afraid of?"</td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" value="" checked>
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" value="" checked>
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                                                            </td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" checked>
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Create 4 Invisible User Experiences you Never Knew About</td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" value="">
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Read "Following makes Medium better"</td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox" value="" disabled>
                                                                        <span class="form-check-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>Unfollow 5 enemies from twitter</td>
                                                            <td class="td-actions text-right">
                                                                <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-link">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-link">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer ">
                                            <hr>
                                            <div class="stats">
                                                <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    @yield('content')
                    
                    <footer class="footer">
                        <div class="container-fluid">
                            <nav>
                                <p class="copyright text-center">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script>
                                    <a href="#">Jake James Manzon</a>, made with love and passion:). v1.0
                                </p>
                            </nav>
                        </div>
                    </footer>
            </div>
        </div>
    </div>


    @yield('modals')

    <!-- Scripts -->    
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    @yield('endBodyScript')
    <script src="{{ asset('assets/finalTemplate/js/core/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/finalTemplate/js/core/bootstrap.min.js')}}" ></script>
    <!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
    <script src="{{ asset('assets/finalTemplate/js/light-bootstrap-dashboard.js')}}" type="text/javascript"></script>

</body>

</html>
