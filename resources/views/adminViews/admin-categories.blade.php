@extends('layouts.app')
@section('categories_link')
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
            $('#categoriesDataTable').DataTable({
              "destroy": true,
              "processing": true, 
              "serverSide": true,
              "colReorder": true,  
              //"autoWidth": true,
              "pagingType": "full_numbers",

              "ajax":  "{{ route('categories.getCategories') }}",
              "columns": [
                  {data: 'categoryName'},
                //   {data: 'status'},
                  {data: 'action'},
              ]
            });

            $('#formAddCategory').on('submit',function(e){
                e.preventDefault();
                var data = $(this).serialize();
                  $.ajax({
                    type:'POST',
                    url: "{{route('admin.createCategories')}}",
                    data: data,
                    success:function(data){
                        if(data === "successful"){
                            console.log("categories created successful");
                            //close modal
                            $('#addCategory').modal('hide')   
                            document.getElementById("formAddCategory").reset(); //reset the form
                            $("#categoriesDataTable").DataTable().ajax.reload();//reload the dataTables
                        }
                    },
                    error:function(data){
                        console.log("error!")
                    }
                });
            });

            $('#formEditCategory').on('submit',function(e){
              e.preventDefault();
              var data = $(this).serialize();

                $.ajax({
                    type:'POST',
                    url: "{{route('admin.editCategory')}}",
                    data: data,

                    success:function(data){
                        $('#editCategoryModal').modal('hide')   //close modal
                        document.getElementById("formEditCategory").reset(); //reset the form
                        $("#categoriesDataTable").DataTable().ajax.reload();//reload the dataTables
                    },

                    error:function(data){
                        console.log("error!")
                    }

                });

            });

         });
         function insertDataToEditCategoryModal(button){
            var data  = $(button.parentNode.parentNode.parentNode.innerHTML).slice(0,-1);
            console.log(data)
            document.getElementById("categoryName").value = data[0].innerHTML;
            document.getElementById("categoryId").value = button.parentNode.parentNode.lastChild.id;

        }
        function formUpdateCategoryStatus(categoryStatus,categoryId){
            //0 == INACTIVE && 1 == ACTIVE
         var fullRoute = "/admin/categories/updateStatus"; //id
            $.ajax({
                type:'Post',
                url: fullRoute,
                dataType:'json',
                data:{
                    'categoryId':categoryId,
                    'categoryStatus': categoryStatus 
                },
                success:function(data){                                 
                    $("#categoriesDataTable").DataTable().ajax.reload();//reload the dataTables                        
                }
            });

        }

    </script>
@endsection


@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">

            {{-- <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header" style="background-color:red;font-color:white">!UNDER CONSTRUCTION!</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div> --}}

            <div class="col-md-4 ">
                <p>
                    <a href = "#addCategory" data-toggle="modal">
                        <button type="button" class="btn btn-success"><i class=" fa fa-plus"></i> Add Category</button>
                    </a>
                </p>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="content table-responsive table-full-width">
                    <table class="table table-bordered table-striped" id="categoriesDataTable">
                        <thead>
                            <tr>
                                <th class="text-left">Category Name</th>
                                {{-- <th class="text-left">Status</th> --}}
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

<div id="addCategory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true"> 
    <div class = "modal-dialog modal-md">
        <div class = "modal-content">
            
            {!! Form::open(['method'=>'get','id'=>'formAddCategory']) !!}
            <input type="hidden" id="_token" value="{{ csrf_token() }}">

            <div class="modal-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="modal-title"><i class="fa fa-cube" style="margin-right: 8px"></i> Add Category</h3>
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
                                    {{Form::label('CategoryName', 'Category Name:')}}
                                </div>
                                <div class="col-md-9">
                                    {{Form::text('CategoryName','',['class'=>'form-control','value'=>''])}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{Form::label('Status', 'Status:')}}
                                    </div>
                                    <div class="col-md-9">
                                        {{Form::select('Status',['active'=>'Active','inactive'=>'Inactive'],'Active',['class'=>'form-control '])}}                                          
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
<div id="editCategoryModal" class="modal fade" tabindex="-1" role = "dialog" aria-labelledby = "viewLabel" aria-hidden="true">
        <div class = "modal-dialog modal-md">
            <div class = "modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><i class=" fa fa-edit" style="margin-right: 10px"></i> Edit</h3>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                
                {!! Form::open(['method'=>'post','id'=>'formEditCategory']) !!}
                <input type="hidden" id="categoryId" name="categoryId" >
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>
                                <span class="glyphicon glyphicon-th"></span>
                                Information
                            </strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{Form::label('categoryName', 'Category Name:')}}
                                    </div>
                                    <div class="col-md-9">
                                        {{Form::text('categoryName','',['class'=>'form-control','id'=>'categoryName'])}}
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group">   
                                <div class="row">
                                    <div class="col-md-3">                                                             
                                        {{Form::label('categoryPrice', 'Price:')}}
                                    </div>
                                    <div class="col-md-9">                                 
                                        {{Form::number('categoryPrice','',['class'=>'form-control','id'=>'categoryPrice','min'=>'1'])}}
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="text-right">                                           
                                <div class="col-md-12">   
                                    <button  type="submit" class="btn btn-success">Save</button>
                                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection