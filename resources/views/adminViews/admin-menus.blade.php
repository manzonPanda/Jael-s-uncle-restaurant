@extends('layouts.app')
@section('menus_link')
class="active"
@endsection

@section('headScript')
    <!--jquery-->
    <script src="{{asset('assets/js/jquery.3.2.1.min.js')}}" type="text/javascript"></script>
    {{--  plugin DataTable  --}}
    <script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.buttons.min.js')}}"></script>
    {{--  DataTable CSS  --}}
    <link href="{{asset('assets/css/datatables.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/buttons.dataTables.min.css')}}" rel="stylesheet"/>
    
    <script type="text/javascript">
         $(document).ready(function(){

            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
             });
        //   $.fn.dataTable.ext.errMode = 'throw';
        $('#menusDataTable').DataTable({
              "destroy": true,
              "processing": true, 
              "serverSide": true,
              "colReorder": true,  
              //"autoWidth": true,
              "pagingType": "full_numbers",

              "ajax":  "{{ route('menus.getMenus') }}",
              "columns": [
                  {data: 'image'},
                  {data: 'name'},
                  {data: 'price'},
                  {data: 'category'},
                  {data: 'status'},
                  {data: 'action'},
              ]
            });

         });

    </script>
@endsection



@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                        <div class="card-header" style="background-color:slategrey;color:white">Manage Menus</div>
                </div>
            </div>
            
            <div class="col-md-4 ">
                <p>
                    <a href = "#addMenu" data-toggle="modal">
                        <button type="button" class="btn btn-success"><i class=" fa fa-plus"></i> Add Menus</button>
                    </a>
                    <a href = "#viewMenus" data-toggle="modal">
                            <button type="button" class="btn btn-info"><i class=" fa fa-plus"></i> View Menus</button>
                    </a>
                </p>
            </div>

        </div>
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="content table-responsive table-full-width">
                    <table class="table table-bordered table-striped" id="menusDataTable">
                        <thead>
                            <tr>
                                <th class="text-left">Image</th>
                                <th class="text-left">Product</th>
                                <th class="text-left">Price</th>
                                <th class="text-left">Category</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')

<div id="addMenu" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true"> 
    <div class = "modal-dialog modal-md">
        <div class = "modal-content">
            
            {!! Form::open(['method'=>'get','id'=>'formAddMenu']) !!}
            <input type="hidden" id="_token" value="{{ csrf_token() }}">

            <div class="modal-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="modal-title"><i class="fa fa-cube" style="margin-right: 8px"></i> Add Menu</h3>
                    </div>
                </div>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class = "modal-body">  
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <span class="glyphicon glyphicon-info-sign"></span>
                            Information
                        </strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {{Form::label('ProductName', 'Product Name:')}}
                                </div>
                                <div class="col-md-9">
                                    {{Form::text('ProductName','',['class'=>'form-control','value'=>''])}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {{Form::label('Price', 'Price:')}}
                                </div>
                                <div class="col-md-9">
                                    {{Form::text('Price','',['class'=>'form-control','value'=>''])}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {{Form::label('Description', 'Description:')}}
                                </div>
                                <div class="col-md-9">
                                    {{Form::text('Description','',['class'=>'form-control','value'=>''])}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {{Form::label('Category', 'Category:')}}
                                </div>
                                <div class="col-md-9">
                                    {{Form::text('Category','',['class'=>'form-control','value'=>''])}}
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{Form::label('Status', 'Status:')}}
                                    </div>
                                    <div class="col-md-9">
                                        {{Form::select('radioButton',['active'=>'Active','inactive'=>'Inactive'],'Active',['class'=>'form-control '])}}                                          
                                    </div>
                                    
                                </div>
                        </div>
                        <div class="row">
                                <div class="text-right">                                           
                                    <div class="col-md-12">   
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="viewMenus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true"> 
    <div class = "modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="modal-title"><i class="fa fa-cube" style="margin-right: 8px"></i> Menus</h3>
                        </div>
                    </div>
                    <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class = "modal-body">  
                <div class="panel panel-default">
                    <div class="panel-heading">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:red;color:white;text-align:center">!UNDER CONSTRUCTION!</div>

                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection