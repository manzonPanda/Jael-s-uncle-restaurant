@extends('layouts.app')
@section('settings_link')
class="active"
@endsection

@section('endBodyScript')
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/finalTemplate/js/core/jquery.3.2.1.min.js')}}"></script>
@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header" style="background-color:red;color:white">COMING SOON</div>

                    <div class="card-body" style="height:700px;background-image: url(/assets/img/underConstruction.jpg)">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection