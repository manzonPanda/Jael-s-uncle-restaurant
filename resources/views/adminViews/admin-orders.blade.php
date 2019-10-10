@extends('layouts.app')
@section('orders_link')
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
           $('#manageOrdersDataTable').DataTable({
             "destroy": true,
             "processing": true, 
             "serverSide": true,
             "colReorder": true,  
             //"autoWidth": true,
             "pagingType": "full_numbers",

             "ajax":  "{{ route('orders.getManageOrders') }}",
             "columns": [
                 {data: 'receiptNumber'},
                 {data: 'totalProducts'},
                 {data: 'date&time'},
                 {data: 'paidStatus'},
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
                        <div class="card-header" style="background-color:slategrey;color:white">Customer Order</div>
                </div>
            </div>
        </div>

        <div class="row" >
            <div class="col-lg-12 col-md-12 col-sm-12" >
                        {!! Form::open(['method'=>'post','id'=>'formSales']) !!}                                
                        <h4 ng-bind="name"></h4>
                        <div class="row">
                            <div class="col-md-3" >                        
                                {{Form::label('receiptNumber', 'Receipt Number:')}}
                                {{Form::number('receiptNumber','',['class'=>'form-control','oninput'=>'enablePrintButton(this)','onchange'=>'saveReceiptNumber(this)'])}}
                            </div>
                            <div class="col-md-3" margin>
                                    {{Form::label('address', 'Address:')}}
                                    {{Form::text('address','',['class'=>'form-control','oninput'=>'enablePrintButton(this)','onchange'=>'saveCustomerAddress(this)'])}}    
                            </div>
                            <div class="col-md-3" margin >
                                {{Form::label('customerName', 'Customer Name:')}}
                                {{Form::text('customerName','',['class'=>'form-control','oninput'=>'enablePrintButton(this)','onchange'=>'saveCustomerName(this)'])}}
                            </div>
                            <div class="col-md-3" margin >
                                {{Form::label('Date', 'Date:')}}
                                <input type="date" name="Date" id="today"  oninput="enablePrintButton(this)"  class="form-control"/>    
                            </div>
                        </div>        
                        <br>
                        <div class="row" > 
                            <div class="col-md-12 table-responsive">
                                <table class="content table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-left" style="color:black">Product</th>
                                            <th class="text-left" style="color:black">image</th>
                                            <th class="text-left" style="color:black">Qty</th>
                                            <th class="text-left" style="color:black">Amount</th>
                                            <th class="text-left" style="color:black">Action</th>
                                        </tr> 
        
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8 text-right">
                                            <label>Total Amount:</label>
                                        </div>
                                        <div class="col-md-3" id="totalSalesDiv">
                                            <p class="form-control" id="totalSales" ng-bind="" style="float: right"></p>
                                        </div>
                                        <div class="col-md-8 text-right">
                                                <label>Vat 13%:</label>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="form-control" style="float: right"></p>
                                        </div>
                                        <div class="col-md-8 text-right">
                                                <label>Discount:</label>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="form-control" style="float: right"></p>
                                        </div>

                                        <div>                                           
                                            <div class="col-md-12">   
                                                <button class="btn btn-primary" type="submit">Submit</button>
                                                <button id="printButton" class="btn btn-success" type="button" onclick="printReceipt()" disabled> Print</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div> 
                        </div>
                            {!! Form::close() !!}
        
            </div>
        </div>

        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header" style="background-color:slategrey;color:white">Manage Orders</div>
                    </div>
                </div>
        </div>

        <div class="row ">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="content table-responsive table-full-width">
                        <table class="table table-bordered table-striped" id="manageOrdersDataTable">
                            <thead>
                                <tr>
                                    <th class="text-left">Reciept #</th>
                                    <th class="text-left">Total Products</th>
                                    <th class="text-left">Date & Time</th>
                                    <th class="text-left">Paid Status</th>
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