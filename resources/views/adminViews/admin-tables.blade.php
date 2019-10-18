@extends('layouts.app')
@section('tables_link')
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
			 
            $('#formAddTable').on('submit',function(e){
                e.preventDefault();
                var data = $(this).serialize();
                  $.ajax({
                    type:'POST',
                    url: "{{route('admin.createTable')}}",
                    data: data,
                    success:function(data){
                        if(data === "successful"){
                            console.log("table created successful");
                            //close modal
                            $('#addTable').modal('hide')   
                            document.getElementById("formAddTable").reset(); //reset the form
                            $("#manageTablesDataTable").DataTable().ajax.reload();//reload the dataTables
                        }
                    },
                    error:function(data){
                        console.log("error!")
                    }
                });
            });
            
             $('#manageTablesDataTable').DataTable({
               "destroy": true,
               "processing": true, 
               "serverSide": true,
               "colReorder": true,  
               //"autoWidth": true,
               "pagingType": "full_numbers",
               "ajax":  "{{ route('tables.getManageTables') }}",
               "columns": [
                   {data: 'tableName'},
                   {data: 'capacity'},
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
                {{-- <div class="card"> --}}
                    {{-- <div class="card-header" style="background-color:red;font-color:white">Manage Tables</div> --}}
					
                    <div class="col-md-4 ">
                      <p>
                        <a href = "#addTable" data-toggle="modal">
                          <button type="button" class="btn btn-success"><i class=" fa fa-plus"></i> Add Table</button>
                        </a>
                      </p>
                    </div>
					
                {{-- </div> --}}
            </div>
        </div>
        
        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card">
                      <div class="card-header" style="background-color:slategrey;color:white">Manage Tables</div>
                  </div>
              </div>
        </div>
          <div class="row ">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="content table-responsive table-full-width">
                      <table class="table table-bordered table-striped" id="manageTablesDataTable">
                          <thead>
                              <tr>
                                  <th class="text-left">Table Name</th>
                                  <th class="text-left">Capacity</th>
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
	<div id="addTable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true"> 
		<div class = "modal-dialog modal-md">
			<div class = "modal-content">
			
				{!! Form::open(['method'=>'get','id'=>'formAddTable']) !!}
				<input type="hidden" id="_token" value="{{ csrf_token() }}">

				<div class="modal-header">
					<div class="row">
						<div class="col-md-12">
							<h3 class="modal-title"><i class="fa fa-cube" style="margin-right: 8px"></i> Add Table</h3>
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
										{{Form::label('TableName', 'Table Name:')}}
									</div>
									<div class="col-md-9">
										{{Form::text('TableName','',['class'=>'form-control','value'=>''])}}
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{{Form::label('Capacity', 'Capacity: ')}}
									</div>
									<div class="col-md-9">
										{{Form::number('Capacity','',['class'=>'form-control','value'=>''])}}
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
    <div id="editTableModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true">

    </div>
@endsection

