@extends('layouts.app')
@section('dashboard_link')
class="active"
@endsection

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ADMIN Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as ADMIN!
                </div>
            </div>
        </div>
    </div>
</div> --}}

      <!--/.Navbar -->
{{-- <div class="wrapper">
    <div class="main-panel">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" >
                        <div class="header">
                            <div class="row">
                                <div class="panel-heading" >
                                    <div class="row">
                                            <div class="panel panel-box clearfix">
                                                    <div class="panel-icon pull-left bg-green">
                                                        <i class="glyphicon glyphicon-user"></i>
                                                    </div>
                                                    <div class="panel-value">
                                                        <h2 class="margin-top">0</h2>
                                                        <p class="text-muted" >Users</p>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}



    <div class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">content_copy</i>
                    </div>
                    <p class="card-category">Used Space</p>
                    <h3 class="card-title">49/50
                        <small>GB</small>
                    </h3>
                    </div>
                    <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-danger">warning</i>
                        <a href="#pablo">Get More Space...</a>
                    </div>
                    </div>
                </div>
                </div> --}}
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            {{-- <i class="material-icons">store</i> --}}
                            <i class="fa fa-users fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Today's Costumer</p>
                        <h3 class="card-title">15</h3>
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                            Last 4 Hours
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-list fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Menus</p>
                        <h3 class="card-title">75</h3>
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">local_offer</i> Add | Create | Edit
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                                <i class="fa fa-credit-card fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Today's Sale</p>
                        <h3 class="card-title">Php 5000</h3>
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                                <i class="fa fa-usd" aria-hidden="true"></i> View 
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
