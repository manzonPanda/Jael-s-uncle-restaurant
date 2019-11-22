@extends('layouts.app')
@section('dashboard_link')
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
<style>
.scrollable-menu{
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
}
</style>    
<script type="text/javascript">
    $(document).ready(function(){

       $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
        });

        $('#summaryBreakdownTable').DataTable({
             "scrollX":true,
             "destroy": true,
             "processing": true, 
             "serverSide": true,
             "colReorder": true,  
             //"autoWidth": true,
             "pagingType": "full_numbers",
             "ajax":  "{{ route('dashboard.getSummaryBreakdownTable') }}",
             "columns": [
                 {data: 'created_at'},
                 {data: 'action'},
             ]
           });

    });

    function summaryBreakdown(button){
        var dateSelected = button.parentNode.parentNode.previousSibling.innerHTML.split(" ")[0];
        console.log(dateSelected)
        // var dateSelected = "2019-11-22 10:40:46";
        
            $.ajax({
                    method:'GET',
                    url: 'admin/viewSummaryBreakdown/' + dateSelected,
                    dataType:"json",
                    success: function(data){
                        console.log(data)
                        document.getElementById("breakdownTitleInfo").innerText = "Breakdown for "+dateSelected;
                        var thatTbody = document.getElementById("breakDownTbody");
                        $("#breakDownTbody tr").remove();                        
                        for(var i = 0; i<data.length; i++){
                            var lastRow = thatTbody.rows[thatTbody.rows.length-1]; //get last row of the table
                            var newRow = thatTbody.insertRow(-1);
                            newRow.insertCell(-1).innerHTML = "<td colspan=\"2\" style='text-align:right'>" +data[i].name+" - "+ data[i].size+"</td>";
                            newRow.insertCell(-1).innerHTML = "<td >" +data[i].totalOrder+ "</td>";
                        }
                        
                    }
            });
    }

</script>
@endsection


@section('endBodyScript')
    <!--   Core JS Files   -->
    {{-- <script src="{{ asset('assets/finalTemplate/js/core/jquery.3.2.1.min.js')}}"></script> --}}
    <script src="{{ asset('assets/js/chart.min.js')}}" charset="utf-8"></script>
    <script src="{{ asset('assets/js/highcharts.min.js')}}" charset="utf-8"></script>
    {!! $chart->script() !!}
{!! $chart2->script() !!}
{!! $mainChart->script() !!}
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
                <div class="col-lg-4 col-md-4 col-sm-4 col">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            {{-- <i class="material-icons">store</i> --}}
                            <i class="fa fa-users fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Total Orders of the day</p>
                        <h3 class="card-title">{{$todayOrders}}</h3>
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                            {{-- Last 4 Hours --}}
                            {{-- {{\Carbon\Carbon::today()}} --}}
                            View
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-list fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Total Menus</p>
                        <h3 class="card-title">0</h3>
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons"></i> Add | Create | Edit
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                                <i class="fa fa-credit-card fa-4x" aria-hidden="true"></i>
                        </div>
                        <p class="card-category">Total Sales of the day</p>
                        <h3 class="card-title">Php {{$todaySales}} </h3>
                        {{-- <h3 class="card-title">{{\Carbon\Carbon::now()->toDateTimeString()}} </h3> --}}
                        </div>
                        <div class="card-footer">
                        <div class="stats">
                                <i class="fa fa-usd" aria-hidden="true"></i> View 
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-4 col">
                    <div class="btn-group" role="group">

                            <button id="dailyButton" type="button" class="btn btn-primary">Daily</button>

                        <div class="btn-group" role="group">
                            <button id="dailyButton" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Weekly</button>
                            <div class="dropdown-menu" aria-labelledby="dailyButton">
                                <a class="dropdown-item" href="#">1</a>
                                <a class="dropdown-item" href="#">2</a>
                                <a class="dropdown-item" href="#">3</a>
                                <a class="dropdown-item" href="#">4</a>
                            </div>
                        </div>

                        <div class="btn-group" role="group">
                            <button id="dailyButton" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Monthly</button>
                            <div class="dropdown-menu" aria-labelledby="dailyButton">
                                <a class="dropdown-item" href="#">January</a>
                                <a class="dropdown-item" href="#">February</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-header" style="background-color:slategrey;color:white">Menu Item Popularity
                            <div class="btn-group" role="group">
                                <button id="dropdownMainCourseSelection" style="color:white;border-color:white" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Main Course selection</button>
                                <div class="dropdown-menu scrollable-menu" aria-labelledby="dailyButton" id="mainCourseSelectionDropdown">
                                {{-- <a class="dropdown-item" href="#">{{$mainCourseSelectionDropdown}}</a> --}}
                                    @foreach ($mainCourseSelectionDropdown as $item)
                                        <a class="dropdown-item" href="#">{{$item['name']." - ".$item['size']}}</a>
                                    @endforeach

                                </div>
                            </div> 
                            <div class="btn-group" role="group">
                                <button id="dropdownDrinkSelection" style="color:white;border-color:white" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Drink selection</button>
                                <div class="dropdown-menu scrollable-menu" aria-labelledby="dailyButton">
                                    @foreach ($drinkSelectionDropdown as $item)
                                        <a class="dropdown-item" href="#">{{$item['name']." - ".$item['size']}}</a>
                                     @endforeach
                                </div>
                            </div> 
                            <div class="btn-group" role="group">
                                <button id="dropdownDessertSelection" style="color:white;border-color:white" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dessert selection</button>
                                <div class="dropdown-menu scrollable-menu" aria-labelledby="dailyButton">
                                    @foreach ($dessertSelectionDropdown as $item)
                                        <a class="dropdown-item" href="#">{{$item['name']." - ".$item['size']}}</a>
                                    @endforeach
                                </div>
                            </div> 
                        </div>
                    </div>    
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-4 col">
                        <img src="{{asset('assets/img/icedCoffee.jpg')}}" class="d-block w-100" alt="...">
                    </div>
                    <div class="col-log-7 col">
                        <h5>Orders of the month</h5>
                        <div>
                            {!! $mainChart->container() !!}
                        </div>
                    </div>
                </div>
           
                <br>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 ">
                    <div class="card">
                        <div class="row" style="background-color:slategrey">
                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                    <div class="card-header" style="background-color:slategrey;color:white">Ordering Breakdown</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5>Total orders for morning-lunch-dinner : Total orders for dinein-takeout</h5>
                        <div class="row">
                            <div class="col-lg-6 col">
                                {!! $chart->container() !!}
                            </div>
                            
                            <div class="col-lg-6 col">
                                {!! $chart2->container() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 ">
                    <div class="card">
                        <div class="row" style="background-color:slategrey">
                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                    <div class="card-header" style="background-color:slategrey;color:white">Summary Breakdown</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5>Orders of the day (dataTables)</h5>
                        <table class="table table-bordered table-striped" style="width:100%" id="summaryBreakdownTable">
                            <thead>
                                <tr>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Acion</th>
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

<div id="summaryBreakdown" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p></p>
                <h3 id="breakdownTitleInfo"> <i class="fa fa-exclamation-triangle" style="margin-right: 15px"> </i> </h3>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
 
            <div class="modal-body">
                    <div class="content table-responsive">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Total Order</th>
                                </tr>
                            </thead>
                            <tbody  id="breakDownTbody">

                            </tbody>
                        </table>
                    </div>
                <div class="text-center">
                    <div class="form-group clearfix">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
