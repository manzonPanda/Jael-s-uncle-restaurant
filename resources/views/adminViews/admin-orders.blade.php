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
            //$.fn.dataTable.ext.errMode = 'throw';
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
        //Helpers
        function setMultipleAttributes(el, attrs) {
		
            for(var key in attrs) {
                el.setAttribute(key, attrs[key]);
            }
        }
		function createCarouselInRow(data,currentDiv){
			var newColSm3Div = document.createElement("div");
				newColSm3Div.setAttribute("class","cols-sm-3");
			var newCardBgDark = document.createElement("div");
				newCardBgDark.setAttribute("class","card bg-dark");
			var newImage = document.createElement("img");
				setMultipleAttributes(newImage,{"class":"card-img-top","src":data[0].image});
			var newCardBody = document.createElement("div");
				newCardBody.setAttribute("class","card-body");
			var newH5 = document.createElement("h5");
				setMultipleAttributes(newH5,{"class":"card-title text-white"})
				var productName = document.createTextNode(data[0].productName);
				newH5.appendChild(productName);
			var newPriceTag = document.createElement("p");
				setMultipleAttributes(newPriceTag,{"class":"card-text text-white"});
				var price = document.createTextNode(data[0].price);
				newPriceTag.appendChild(price);
			var newButton = document.createElement("a");
				setMultipleAttributes(newButton,{"href":"#","class":"btn bt-primary"});
				var textNode = document.createTextNode("Add Order");
				newButton.appendChild(textNode);
			newCardBody.appendChild(newH5);
			newCardBody.appendChild(newPriceTag);
			newCardBody.appendChild(newButton);
			newCardBgDark.appendChild(newImage);
			newCardBgDark.appendChild(newCardBody);
			newColSm3Div.appendChild(newCardBgDark);
			document.getElementById("carouselDiv"+currentDiv).appendChild(newColSm3Div);
		}
        function createCarousel(currentDiv){
			//initialize nth li(indicator) and nth DV1
			var newIndicatorElement = document.createElement("li");
				setMultipleAttributes(newIndicatorElement, {"data-target": "#carouselExampleIndicators", "data-slide-to": "0"});
				document.getElementById("carouselExampleIndicators").children[0].appendChild(newIndicatorElement);
			var newDiv = document.createElement("div");
				setMultipleAttributes(newDiv,{"class":"carousel-item active","id":"carouselDiv"+currentDiv})
				var newRow = document.createElement("div");
				newRow.setAttribute("class","row");
				newDiv.appendChild( newRow );
				document.getElementById("carouselExampleIndicators").children[1].appendChild(newDiv);
		}
		function displayMenusInCarousel(button){
            console.log(button.innerHTML);
            var fullRoute = "/admin/orders/getMenusToCarousel/"+button.innerHTML;
            $.ajax({
                type:'GET',
                url:fullRoute,
                // data: {
                //     'category':button.innerHTML
                // },
                success:function(data){
                    console.log(data)
                    var currentDiv = 1;
                    var numberOfMenuPerCarousel = 4;
                    //create nth li(indicator) and nth DV1
                    createCarousel(currentDiv);
                    for (var i = 0; i < data.length; i++) {
                        if( numberOfMenuPerCarousel / i > 1){
                            //insert index(i) to the current DIV
                           createCarouselInRow(data,currentDiv);
                        }else{
                            //add another DIV and insert index(i)
							currentDiv++;
							numberOfMenuPerCarousel += 4;
							//create nth li(indicator) and nth DV1
							createCarousel(currentDiv);
							//then insert index(i) to the current DIV
							createCarouselInRow(data,currentDiv);
                        } 
                        
                    }
                    // var olElement = document.createElement("ol");
                    // olElement.className = "carousel-indicators";
                    // var liElement = document.createElement("li");
                    // setMultipleAttributes(liElement, {"data-target": "#carouselExampleIndicators", "data-slide-to": "0"});
                    // olElement.appendChild(liElement);
                    // document.getElementById("carouselExampleIndicators").appendChild(olElement);
						
						//========= DIV1 - DIV2 -  DIV3 ... =========
                    //  <div class="row" > =========1 row for every 4=========
                    //     <div class="col-sm-3"> ====================== x4 ===============================
                    //         <div class="card bg-dark " >
                    //             <img class="card-img-top" alt="card image cap" src="{{asset('assets/img/icedCoffee.jpg')}}">
                    //                 <div class="card-body" >
                    //                         <h5 class="card-title text-white">
                    //                             Iced Coffee Cream
                    //                         </h5>
                    //                         <p class="card-text text-white">Php 59.00</p>
                    //                         <a href="#" class="btn btn-primary">Add Order</a>
                    //                 </div>
                    //         </div>
                    //     </div>
                    // </div>
                    // document.getElementById("carouselExampleIndicators").innerHTML = '\
                    //         <ol class="carousel-indicators">\
                    //               <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>\
                    //             </ol>\
                    //             <div class="carousel-inner">\
                    //               <div class="carousel-item active">\========================= DIV 1 ================================
                    //                 <img src="{{asset('assets/img/greenTea.jpg')}}" class="d-block w-100" alt="...">\
                    //               </div>\
                    //               <div class="carousel-item">\========================= DIV 2 ================================
                    //                   <img src="{{asset('assets/img/icedCoffee.jpg')}}" class="d-block w-100" alt="...">\
                    //                 </div>\
                    //                 <div class="carousel-item">\========================= DIV 3 ================================
                    //                     <img src="{{asset('assets/img/icedPulpy.jpg')}}" class="d-block w-100" alt="...">\
                    //               </div>\
                    //             </div>\
                    //             <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">\
                    //                 <span class="carousel-control-prev-icon" aria-hidden="true"></span>\
                    //               <span class="sr-only">Previous</span>\
                    //             </a>\
                    //             <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">\
                    //               <span class="carousel-control-next-icon" aria-hidden="true"></span>\
                    //               <span class="sr-only">Next</span>\
                    //             </a>\   
                    //             ';
                },
                error:function(data){
                
                }
            });
        }
   </script>

@endsection


@section('content')
<div class="content">

    <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{-- <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Orders</div>
                    </div> --}}
                    <div class="content table-responsive table-full-width">
                        <table class="table table-bordered table-striped" >
                            <thead>
                            </thead>
                            <tbody>
                                <tr >
                                    <td  style="border:1px solid black" class="text-center">All</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Inapoy (Rice)</td>
                                    <td style="border:1px solid black" class="text-center">Vegetable</td>
                                    <td style="border:1px solid black" class="text-center">All Time Fav Meryenda</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Drinks</td>
                                    <td style="border:1px solid black" class="text-center">Partner El Meryenda</td>
                                    <td style="border:1px solid black" class="text-center">Main Courses</td>
                                    <td style="border:1px solid black" class="text-center">Rapsilog</td>
                                    <td style="border:1px solid black" class="text-center">Partner Meal</td>
                                    <td style="border:1px solid black" class="text-center">Pizza De Alfredo</td>
                                    <td style="border:1px solid black" class="text-center">Additional</td>
                                    <td style="border:1px solid black" class="text-center">Pulotan/Appetizer</td>
                                    <td style="border:1px solid black" class="text-center">Desserts</td>
                                    <td style="border:1px solid black" class="text-center">Drinks And Beverages</td>
                                    <td style="border:1px solid black" class="text-center">All Time Fav Shakes</td>
                                    <td style="border:1px solid black" class="text-center">Buckets</td>
                                    <td style="border:1px solid black" class="text-center">Noodles and Pasta</td>
                                    <td style="border:1px solid black" class="text-center">Bilao</td>

                                </tr>
                            </tbody>
                        </table>   
                    </div>
                </div>
            </div>

        <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Drinks</div>
                    </div>
                    {{-- Carousel by 4 images Example--}}
                    {{-- <div class="row" >
                        <div class="col-sm-3">
                            <div class="card bg-dark " >
                                <img class="card-img-top" alt="card image cap" src="{{asset('assets/img/icedCoffee.jpg')}}">
                                    <div class="card-body" >
                                            <h5 class="card-title text-white">
                                                Iced Coffee Cream
                                            </h5>
                                            <p class="card-text text-white">Php 59.00</p>
                                            <a href="#" class="btn btn-primary">Add Order</a>
                                    </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card bg-dark text-white" >
                                <img class="card-img-top" alt="card image cap" src="{{asset('assets/img/icedPulpy.jpg')}}">
                                    <div class="card-body" >
                                            <h5 class="card-title text-white">
                                                Iced Pulpy Pineapple 
                                            </h5>
                                            <p class="card-text">Php 59.00</p>
                                            <a href="#" class="btn btn-primary">Add Order</a>
                                    </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card bg-dark text-white" >
                                <img class="card-img-top" alt="card image cap" src="{{asset('assets/img/greenTea.jpg')}}">
                                    <div class="card-body" >
                                            <h5 class="card-title text-white">
                                                Green tea 
                                            </h5>
                                            <p class="card-text">Php 39.00</p>
                                            <a href="#" class="btn btn-primary">Add Order</a>
                                        </div>
                                    </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card bg-dark text-white" >
                                <img class="card-img-top" alt="card image cap" src="{{asset('assets/img/greenTea.jpg')}}">
                                    <div class="card-body" >
                                            <h5 class="card-title text-white">
                                                Green tea 
                                            </h5>
                                            <p class="card-text">Php 39.00</p>
                                            <a href="#" class="btn btn-primary">Add Order</a>
                                        </div>
                                    </div>
                        </div>       
                    </div> --}}

                        {{-- Carousel by 4 images Template : Do it here--}}
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                {{-- insert here --}}
                            </ol>
                            <div class="carousel-inner">
                                {{-- insert here --}}
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>


                </div>

           
                <div class="col-lg-6 col-md-6 col-sm-6" >
                    <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Customer Order</div>
                    </div>
                    <div >
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
                                                <th class="text-left" style="color:black">Qty</th>
                                                <th class="text-left" style="color:black">Amount</th>
                                                <th class="text-left" style="color:black">Action</th>
                                            </tr> 
            
                                        </thead>
                                        <tbody>
                                            <td>Green Tea</td>
                                            <td><input style="width: 100px;" type="number" value="1" class="form-control"></td>
                                            <td>39.00</td>
                                            <td><button class="btn btn-danger">X</button><button class="btn btn-success">Options</button></td>
                                        
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
