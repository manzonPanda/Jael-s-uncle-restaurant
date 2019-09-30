@extends('layouts.app')
@section('menus_link')
class="active"
@endsection


@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header" style="background-color:red;font-color:white">!UNDER CONSTRUCTION!</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are in Menus Page!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection