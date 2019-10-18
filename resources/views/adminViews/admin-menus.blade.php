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
            // let today = new Date().toISOString().substr(0, 10);
            // document.querySelector("#today").value = today;

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
                    {data: 'description'},
                    {data: 'size'},
                    {data: 'price'},
                    // {data: 'category'},
                    {data: 'status'},
                    {data: 'action'},
                ]
            });

            $('#formAddMenu').on('submit',function(e){
              e.preventDefault();
              var data = $(this).serialize();

                $.ajax({
                    type:'POST',
                    url: "{{route('admin.createProduct')}}",
                    data: data,

                    success:function(data){
                        $('#addMenu').modal('hide')   //close modal
                        document.getElementById("formAddMenu").reset(); //reset the form
                        $("#menusDataTable").DataTable().ajax.reload();//reload the dataTables
                    },

                    error:function(data){
                        console.log("error!")
                    }

                });

            });

            $('#formCreateBundles').on('submit',function(e){
              e.preventDefault();
              var data = $(this).serialize();

                $.ajax({
                    type:'POST',
                    url: "{{route('admin.createBundle')}}",
                    data: data,

                    success:function(data){
                        $('#createBundles').modal('hide')   //close modal
                        document.getElementById("formCreateBundles").reset(); //reset the form
                       
                    },

                    error:function(data){
                        console.log("error!")
                    }

                });

            });

         });

        //Helpers
        function setMultipleAttributes(el, attrs) {
            for(var key in attrs) {
                el.setAttribute(key, attrs[key]);
            }
        }

         function editMenu(){
             alert("under construction:)")
         }
         function removeRow(a){
            $(a.parentNode.parentNode).hide(500,function(){
                this.remove();  
            });
            // a.parentNode.parentNode.remove();

        }
         function addRow(div){
            if(div.getAttribute("data-modal") === "menus"){
                document.getElementById("categoryInputMenu").value = div.firstChild.innerHTML;
                document.getElementById("searchResultDiv").innerHTML = "";
                var newHiddenInput = document.createElement("input");
                setMultipleAttributes(newHiddenInput,{"type":"hidden","name":"category_id","value":div.getAttribute("id")});
                document.getElementById("categoryInputMenu").parentNode.appendChild(newHiddenInput);
            }else{
                //display bundles in bundleTable
                console.log(div.getAttribute("id"))
                $.ajax({
                    method:'GET',
                    url: 'getBundleForSpecificCategory/' + div.getAttribute("id"),
                    dataType:"json",
                    success: function(data){
                        console.log(data)
                        if(data.length != 0){//if there is bundle
                            var items =[];
                            var thatTable = document.getElementById("bundleTable");
                            
                            $("#bundleTable tr").remove();

                            for (var i = 0; i < data.length; i++) {
                                var newRow = thatTable.insertRow(-1);
                                newRow.insertCell(-1).innerHTML = "<td>" +data[i].name+ "</td>";
                                newRow.insertCell(-1).innerHTML = "<td><input name='quantity[]' type='number' min='1' value='"+data[i].quantity+"' class='form-control'></td>";
                                newRow.insertCell(-1).innerHTML = "<td><input type='hidden' name='product_id[]' value='" +data[i].productId+ "'><button type='button' onclick='removeRow(this)' class='btn btn-danger form-control'><i class='glyphicon glyphicon-remove'></i>X</button></td>";
                            }

                        }else{
                            $("#bundleTable tr").remove();

                        }

                        document.getElementById("searchItemInput").value = "";
                        document.getElementById("searchResultDiv").innerHTML = "";
                    }
                });
                document.getElementById("categoryInputBundles").value = div.firstChild.innerHTML;
                document.getElementById("searchResultDivBundles").innerHTML = "";
                var newHiddenInput = document.createElement("input");
                setMultipleAttributes(newHiddenInput,{"type":"hidden","name":"category_id","value":div.getAttribute("id")});
                document.getElementById("categoryInputBundles").parentNode.appendChild(newHiddenInput);
            }
            
      }
         function displayCategories(a){
            if(a.value === ""){
                if(a.id === "categoryInputMenu")
                    document.getElementById("searchResultDiv").innerHTML ="";   
                document.getElementById("searchResultDivBundles").innerHTML ="";   
          }
            $.ajax({
                    method:'GET',
                    url: 'getCategoriesDropdown/' + a.value,
                    dataType:"json",
                    success:function(data){
                        // console.log(data)
                        if(a.id === "categoryInputMenu"){
                            var resultDiv = document.getElementById("searchResultDiv");
                            resultDiv.innerHTML = "";
                        }else{
                            var resultDivBundles = document.getElementById("searchResultDivBundles");
                            resultDivBundles.innerHTML = "";
                        }
                        
                            for (var i = 0;  i< data.length; i++) {
                                var node = document.createElement("DIV");
                                node.setAttribute("id",data[i].category_id)
                                node.setAttribute("onclick","addRow(this)")
                                if(a.id === "categoryInputMenu"){
                                    node.setAttribute("data-modal","menus")
                                }else{
                                    node.setAttribute("data-modal","bundles")
                                }  
                                var pElement = document.createElement("P");
                                var textNode = document.createTextNode(data[i].categoryName);
                                pElement.appendChild(textNode);
                                node.appendChild(pElement);
                                if(a.id === "categoryInputMenu"){
                                    resultDiv.appendChild(node);  
                                }else{
                                    resultDivBundles.appendChild(node);  
                                }          

                        }

                    }

            });
         }

         function addProductRowInBundleTable(div){
          var items =[];
          var thatTbody = $("#bundleTable tr td:first-child");

          for (var i = 0; i < thatTbody.length; i++) {
              items[i] = thatTbody[i].innerHTML;
          }

          if( items.indexOf(div.firstChild.innerHTML) == -1 ){ //if there is not yet in the table

              var thatTable = document.getElementById("bundleTable");
              var newRow = thatTable.insertRow(-1);

              newRow.insertCell(-1).innerHTML = "<td>" +div.firstChild.innerHTML+ "</td>";
              newRow.insertCell(-1).innerHTML = "<td><input name='quantity[]' type='number' min='1' value='1' class='form-control'></td>";
              newRow.insertCell(-1).innerHTML = "<td><input type='hidden' name='product_id[]' value='" +div.getAttribute("id")+ "'><button type='button' onclick='removeRow(this)' class='btn btn-danger form-control'><i class='glyphicon glyphicon-remove'></i>X</button></td>";
          }
          document.getElementById("searchItemInput").value = "";
          document.getElementById("searchItemResultDiv").innerHTML = "";
      }
         function searchItem(a){
          if(a.value === ""){
              document.getElementById("searchItemResultDiv").innerHTML ="";   
          }
          $.ajax({
                method:'GET',
                url: 'getProductsDropdown/' + a.value,
                dataType:"json",
              success: function(data){
                 console.log(data)

                  var resultDiv = document.getElementById("searchItemResultDiv");
                  resultDiv.innerHTML = "";
                  for (var i = 0;  i< data.length; i++) {
                      var node = document.createElement("DIV");
                      node.setAttribute("id",data[i].product_id)
                      node.setAttribute("onclick","addProductRowInBundleTable(this)")
                      var pElement = document.createElement("P");
                      var textNode = document.createTextNode(data[i].name);
                      pElement.appendChild(textNode);
                      node.appendChild(pElement);          
                      resultDiv.appendChild(node);  

                  }
              }
          });

      }
    </script>
    <style>
    .autocomplete {
        /*the container must be positioned relative:*/
        position: relative;
        display: inline-block;
    }
    .searchResultDiv {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }
    .searchResultDiv div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff; 
        border-bottom: 1px solid #d4d4d4; 
    }
    .searchResultDiv div:hover {
        /*when hovering an item:*/
        background-color: #e9e9e9; 
    }
    .autocomplete-active {
        /*when navigating through the items using the arrow keys:*/
        background-color: DodgerBlue !important; 
        color: #ffffff; 
    }
    </style>
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
            
            <div class="col-md-12 ">
                <p>
                    <a href = "#addMenu" data-toggle="modal">
                        <button type="button" class="btn btn-success"><i class=" fa fa-plus"></i> Add Menus</button>
                    </a>
                    <a href = "#createBundles" data-toggle="modal">
                        <button type="button" class="btn btn-success"><i class=" fa fa-plus"></i> Bundles</button>
                    </a>
                    <a href = "#viewMenus" data-toggle="modal">
                            <button type="button" class="btn btn-info"><i class=" fa fa-eye"></i> View Menus</button>
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
                                <th class="text-left">Description</th>
                                <th class="text-left">Size</th>
                                <th class="text-left">Price</th>
                                {{-- <th class="text-left">Category</th> --}}
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
                                    {{Form::number('Price','',['class'=>'form-control','value'=>''])}}
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
                                        {{Form::label('Size', 'Size:')}}
                                    </div>
                                    <div id="categoryDropdown" class="col-md-9">
                                            {{Form::select('Size',['xs'=>'XS','small'=>'Small','medium'=>'Medium','large'=>'Large','regular'=>'Regular','xl'=>'XL','none'=>'None'],'none',['class'=>'form-control '])}}                
                                        </div>
                                </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {{Form::label('Category', 'Category:')}}
                                </div>
                                <div class="col-md-9">
                                    <div class="autocomplete" style="width:100%;">
                                        {{Form::text('Category','',['id'=>'categoryInputMenu','class'=>'form-control','value'=>'','placeholder'=>'Search category name','onkeyup'=>'displayCategories(this)','autocomplete'=>'off'])}}
                                        <div id="searchResultDiv" class="searchResultDiv"></div>
                                    </div>
                                    {{-- {{Form::text('Category','',['class'=>'form-control','value'=>''])}} --}}
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

<div id="createBundles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true"> 
    <div class = "modal-dialog modal-md">
        <div class="modal-content">
                {!! Form::open(['method'=>'get','id'=>'formCreateBundles']) !!}
                <input type="hidden" id="_token" value="{{ csrf_token() }}">
            <div class="modal-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="modal-title"><i class="fa fa-cube" style="margin-right: 8px"></i> Bundles</h3>
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
                                        <div class="autocomplete" style="width:100%;">
                                            {{Form::text('Category','',['id'=>'categoryInputBundles','class'=>'form-control','value'=>'','placeholder'=>'Search category name','onkeyup'=>'displayCategories(this)','autocomplete'=>'off'])}}
                                            <div id="searchResultDivBundles" class="searchResultDiv"></div>
                                        </div>
                                        {{-- {{Form::text('CategoryName','',['class'=>'form-control','value'=>''])}} --}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{Form::label('Price', 'Price:')}}
                                        </div>
                                        <div class="col-md-9">
                                            {{Form::number('Price','',['class'=>'form-control','value'=>''])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="content table-responsive">
                                        <table class="table table-bordered table-striped" >
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Product</th>
                                                    <th class="text-left">Qty</th>
                                                    <th class="text-left">Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bundleTable">
                                                {{-- <tr>
                                                    <td>Spaghetti platter</td>
                                                    <td>1</td>
                                                    <td><button type="button" onclick="removeRow(this)" class="btn btn-danger form-control"><i class="fa fa-close"></i>X</button></td>
                                                </tr>
                                                <tr>
                                                    <td>Fried Chicken</td>
                                                    <td>5</td>
                                                    <td><button type="button" onclick="removeRow(this)" class="btn btn-danger form-control"><i class="fa fa-close"></i>X</button></td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
            
            
                                    </div> 
                                    <div class="autocomplete" style="width:100%;">
                                        <input autocomplete="off" type="text" id="searchItemInput" onkeyup="searchItem(this)" class="form-control border-input" placeholder="Enter the name of the menu ">
                                        <div id="searchItemResultDiv" class="searchResultDiv">
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