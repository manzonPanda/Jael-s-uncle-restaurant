@extends('layouts.app')
@section('orders_link')
class="active"
@endsection

@section('ng-app')
ng-app="ourAngularJsApp"
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
    
    <!--AngularJs-->
    <script src="{{asset('assets/js/angularJs.js')}}"></script>
    <script src="{{asset('assets/js/angular-datatables.min.js')}}"></script> 

    <script type="text/javascript">
        $(document).ready(function(){

            let today = new Date().toISOString().substr(0, 10);
            document.querySelector("#today").value = today;

           $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
            });

            displayCategoriesButtons();
            displayTableChoices();
            $("#payModalContinueButton").on('click', function(button) {
                button.preventDefault(); //prevent the page to load when submitting form
                var transaction_id = button.currentTarget.getAttribute("data-transaction_id");
                $.ajax({
                    type: 'Post',
                    url: 'customerWillPay/' +transaction_id,
                    success:function(data){
                        console.log(data)
                        $('#payModal').modal('hide');
                       $("#manageOrdersDataTable").DataTable().ajax.reload();//reload the dataTables                                              
                    }
                })

          })

           $('#manageOrdersDataTable').DataTable({
             "scrollX":true,
             "destroy": true,
             "processing": true, 
             "serverSide": true,
             "colReorder": true,  
             //"autoWidth": true,
             "pagingType": "full_numbers",
             "ajax":  "{{ route('orders.getManageOrders') }}",
             "columns": [
                 {data: 'transaction_id'},
                 {data: 'tableId'},
                 {data: 'customer_name'},
                 {data: 'created_at'},
                 {data: 'status'},
                 {data: 'totalPrice'},
                 {data: 'action'},
             ]
           });
 
           $('#formSales').on('submit',function(e){
              e.preventDefault();
              var data = $(this).serialize();

                $.ajax({
                    type:'POST',
                    url: "{{route('admin.createOrder')}}",
                    data: data,

                    success:function(data){
                        console.log(data)
                        location.reload();
                        document.getElementById("formSales").reset(); //reset the form
                        $('#cartTable tr').remove();// delete all rows in table
                        document.querySelector("#today").value = today;
                        
                        localStorage.clear();//clear localStorage
                        document.getElementById("totalSalesDiv").firstChild.innerHTML=""; //clear total sales

                        $("#manageOrdersDataTable").DataTable().ajax.reload();//reload the dataTables
                        alert("Orders completed.")
                    },

                    error:function(data){
                        console.log("error: " +JSON.stringify(data.responseJSON.errors))
                    }

                });

            });


        });

        function insertDataInViewOrderModal(button){
            var receiptNumber = button.parentNode.parentNode.parentNode.firstChild.innerHTML;
            $.ajax({
                    method:'GET',
                    url: 'viewReceiptOrder/' + receiptNumber,
                    dataType:"json",
                    success: function(data){
                        console.log(data)
                        var thatTbody = document.getElementById("orderDetails");
                        $("#orderDetails tr").remove();                        
                        for(var i = 0; i<data.length; i++){
                            var lastRow = thatTbody.rows[thatTbody.rows.length-1]; //get last row of the table
                            if(data[i].categoryPrice > 0){ //if it is bundle
                                if ( i>0 && data[i-1].categoryPrice > 0 ){
                                    lastRow.firstChild.innerHTML += "<br>"+data[i].quantity+" "+data[i].name;

                                    // var secondRow = thatTbody.insertRow(-1);
                                    //     secondRow.setAttribute("style","text-align:center")
                                    //     var cell = secondRow.insertCell(-1);
                                    //         cell.insertAdjacentHTML('afterbegin',"<td colspan=\"2\">" +data[i].quantity+" "+data[i].name+ "</td>");      
                                    //         cell.colSpan = 2;

                                }else{
                                    var newRow = thatTbody.insertRow(-1);
                                        newRow.insertCell(-1).innerHTML = "<td>" +data[i].categoryName+ "</td>";
                                        newRow.insertCell(-1).innerHTML = "<td>" +data[i].quantity+ "</td>";
                                    var secondRow = thatTbody.insertRow(-1);
                                        secondRow.setAttribute("style","text-align:center")
                                        var cell = secondRow.insertCell(-1);
                                            cell.insertAdjacentHTML('afterbegin',"<td colspan=\"2\">" +data[i].quantity+" "+data[i].name+ "</td>");
                                            cell.colSpan = 2;
                                }

                            }else{
                                var newRow = thatTbody.insertRow(-1);
                                newRow.insertCell(-1).innerHTML = "<td colspan=\"2\" style='text-align:right'>" +data[i].name+ "</td>";
                                newRow.insertCell(-1).innerHTML = "<td >" +data[i].quantity+ "</td>";
                            }
                        }
                        
                    }
            });
        }

        function editOrder(button){
            var receiptNumber = button.parentNode.parentNode.parentNode.firstChild.innerHTML;
            
            $.ajax({
                    method:'GET',
                    url: 'editReceipt/' + receiptNumber,
                    dataType:"json",
                    success: function(data){
                        console.log(data)
                    }
            });

        }       
        function insertTransactionIdInButton(button){
                document.getElementById("payModalContinueButton").setAttribute("data-transaction_id",button.getAttribute("data-transaction_id"))
            }
        
        function addTotalSalesInForm(){
            document.getElementById("hiddenTotalPrice").value = document.getElementById("totalSalesDiv").firstChild.innerHTML;
            
        }
        function saveReceiptNumber(e){
            localStorage.setItem("receiptNumber",e.value);       
        }

        function saveCustomerName(e){
            localStorage.setItem("customerName",e.value);       
        }
        function displayCategoriesButtons(){
            var thatDiv = document.getElementById("categoriesButtons");
            $.ajax({
                method:'GET',
                url: 'orders/getCategoriesInOrders',
                dataType:"json",
                success:function(data){
                    console.log(data)
                for(var i = 0; i<data.length;i++){
                        // <button type="button" ng-click="addButton($event)" class="btn btn-secondary">Vegetable</button>
                        var newButton = document.createElement("button");
                            // setMultipleAttributes(newButton,{"class":"btn btn-secondary","ng-click":"displayMenusInCarousel($event)"}); 
                            setMultipleAttributes(newButton,{"class":"btn btn-secondary","onclick":"displayMenusInCarousel("+ data[i].category_id +")"}); 
                            var textNode = document.createTextNode(data[i].categoryName);
                            newButton.appendChild(textNode);
                            thatDiv.appendChild(newButton);

                            // $compile(angular.element(newButton))($scope);
                    }
                }
            });

        }
        function displayTableChoices(){
            var thatDiv = document.getElementById("tableChoices");
            $.ajax({
                method:'GET',
                url: 'orders/getTablesInOrders',
                dataType:"json",
                success:function(data){

                for(var i = 0; i<data.length;i++){
                    // <button class="dropdown-item" type="button">Table 2</button>                        
                    thatDiv.nextElementSibling.innerHTML += "<button class='dropdown-item' type='button' data-tableId="+data[i].tableId+" onclick='tableChanged(this)'>"+data[i].tableName +"</button>";
                    }
                }
            });
        }
        function tableChanged(e){
            console.log(e)
            var thatDiv = document.getElementById("tableChoices");
            thatDiv.innerHTML = e.innerHTML+"<input type='hidden' name='tableId' value='"+e.getAttribute('data-tableId')+"'>";
        }
        //Helpers
        function setMultipleAttributes(el, attrs) {
		
            for(var key in attrs) {
                el.setAttribute(key, attrs[key]);
            }
        }
        function addItemToCart(data){
            console.log(data)
            angular.element(document.getElementById("angularScope")).scope().addButton(data);

        }
		function createCarouselInRow(data,currentDiv,index,isBundle){
			var newColSm3Div = document.createElement("div");
				newColSm3Div.setAttribute("class","col-4 col-sm-4");
            var newCardBgDark = document.createElement("div");
                newCardBgDark.setAttribute("class","card bg-dark");
			var newImage = document.createElement("img");
				setMultipleAttributes(newImage,{"class":"card-img-top","src":data[index].image});
			var newCardBody = document.createElement("div");
				newCardBody.setAttribute("class","card-body");
			var newH5 = document.createElement("h5");
                setMultipleAttributes(newH5,{"class":"card-title text","style":"color:#bfff00"});
            if(isBundle){
                var categName = document.createTextNode(data[index].categoryName);
                    newH5.appendChild(categName);    
            }else{
                var productName = document.createTextNode(data[index].name +"-"+data[index].size);
                    newH5.appendChild(productName);    
            }
                //if it is bundle
                // (document.getElementById("carouselDiv"+currentDiv).firstChild.firstChild) != null &&
                if( isBundle){ //may atlease1Rroduct in Carsl
                    
                    if( document.getElementById("carouselDiv"+currentDiv).firstChild.firstChild != null ){
                        if( isBundle ) { //if bundle, use this query
                            var locationOfBundleInfo = ($(' *[data-category_id] '))[0].parentNode; //find the nearest location //P element
                        }else{
                            var locationOfBundleInfo = ($(' *[data-product_id] '))[0].parentNode; //find the nearest location //P element
                        }   
                        // locationOfBundleInfo.innerHTML += "<br>"+data[index].categoryQuantity+"-"+data[index].name;
                            
                            locationOfBundleInfo.childNodes[1].innerHTML += "<br>"+data[index].categoryQuantity+"-"+data[index].name;
                            // locationOfBundleInfo.insertBefore(newPriceTag,locationOfBundleInfo.childNodes[1])
                            // console.log("in"+data[index].name)
                    }else{
                            // console.log("out"+data[index].name)
                            // var locationOfBundleInfo = ($(' *[data-product_id] '))[0].parentNode; //find the nearest location //P element
                            var bundleProductsElement = document.createElement("p");
                                setMultipleAttributes(bundleProductsElement,{"class":"card-text text-white"});
                                var prodNames = document.createTextNode(data[index].categoryQuantity+"-"+data[index].name);
                                bundleProductsElement.appendChild(prodNames);  
                                newCardBody.appendChild(bundleProductsElement);
                                // locationOfBundleInfo.insertBefore( bundleProductsElement,locationOfBundleInfo.childNodes[1] )
                            
                            var newPriceTag = document.createElement("p");
                            setMultipleAttributes(newPriceTag,{"class":"card-text text-white"});
                            var categPrice = document.createTextNode(data[index].categoryPrice);
                            newPriceTag.appendChild(categPrice);  
                            newCardBody.appendChild(newPriceTag);

                            // locationOfBundleInfo.insertBefore(newPriceTag,locationOfBundleInfo.childNodes[1])
                            
                        }


                }else{ //if not bundle
                    var newPriceTag = document.createElement("p");
                        setMultipleAttributes(newPriceTag,{"class":"card-text text-white"});
                        var price = document.createTextNode(data[index].price);
                        newPriceTag.appendChild(price);  
                        newCardBody.appendChild(newPriceTag);

                } 


            var newButton = document.createElement("button");
                if(isBundle){//it is bundle
                    setMultipleAttributes(newButton,{"style":"background-color:#26d926;color:white;border-radius:15%;border-color:white","class":"btn bt-success btn-block","onclick":"addItemToCart(this)","data-category_id":data[index].categoryId});
                }else{
                    setMultipleAttributes(newButton,{"style":"background-color:#26d926;color:white;border-radius:15%;border-color:white","class":"btn bt-success btn-block","onclick":"addItemToCart(this)","data-product_id":data[index].product_id,"data-product_category_id":data[index].categoryId});
                }
                // ng-click="addButton($event)"
                    //    angular.element(document.getElementById("angularScope")).scope().addButton(data,currentDiv,i);
            var textNode = document.createTextNode("Add");
            newButton.appendChild(textNode);

            // newCardBody.appendChild(newH5);
                newCardBody.insertBefore(newH5,newCardBody.childNodes[0]);

            // newCardBody.appendChild(bundleProductsElement);
            // newCardBody.appendChild(newPriceTag);
            newCardBody.appendChild(newButton);

            newCardBgDark.appendChild(newImage);
            newCardBgDark.appendChild(newCardBody);
            newColSm3Div.appendChild(newCardBgDark);
            
            if(isBundle){
                if( (document.getElementById("carouselDiv"+currentDiv).firstChild.firstChild) === null ){ //if there is no product yet in Carousel
                    document.getElementById("carouselDiv"+currentDiv).firstChild.appendChild(newColSm3Div);
                }else{

                }
            }else{
                document.getElementById("carouselDiv"+currentDiv).firstChild.appendChild(newColSm3Div);
            }
            
		}
        function createCarousel(currentDiv){
			//initialize nth li(indicator) and nth DV1
			var newIndicatorElement = document.createElement("li");
                if(currentDiv == 1){
				    setMultipleAttributes(newIndicatorElement, {"data-target": "#carouselExampleIndicators", "data-slide-to": currentDiv-1,"class":"active"});
                }else{
                    setMultipleAttributes(newIndicatorElement, {"data-target": "#carouselExampleIndicators", "data-slide-to": currentDiv-1});
                    
                }
				document.getElementById("carouselExampleIndicators").children[0].appendChild(newIndicatorElement);
			var newDiv = document.createElement("div");
                if(currentDiv == 1){
				    setMultipleAttributes(newDiv,{"class":"carousel-item active","id":"carouselDiv"+currentDiv})
                }else{
				    setMultipleAttributes(newDiv,{"class":"carousel-item","id":"carouselDiv"+currentDiv})
                }
				var newRow = document.createElement("div");
                newRow.setAttribute("class","row");
				newDiv.appendChild( newRow );
				document.getElementById("carouselExampleIndicators").children[1].appendChild(newDiv);
        }
        
		function displayMenusInCarousel(button){
            // console.log(button.innerHTML);
            //clean Div of Carousel
            document.getElementById("carouselExampleIndicators").childNodes[1].innerHTML = "";
            document.getElementById("carouselExampleIndicators").childNodes[3].innerHTML = "";

            var fullRoute = "/admin/orders/getMenusToCarousel/"+button;
            $.ajax({
                type:'GET',
                url:fullRoute,
                dataType:'json',
                // data: {
                //     'category':button.innerHTML
                // },
                success:function(data){
                    console.log(data)
                    var currentDiv = 1;
                    var numberOfMenuPerCarousel = 3;
                    //create nth li(indicator) and nth DV1
                    createCarousel(currentDiv);
                    for (var i = 0; i < data.length; i++) {
                        if( numberOfMenuPerCarousel / i > 1){
                            //insert index(i) to the current DIV
                            //if next data has a CategoryPrice, meaning it is part of a bundle, then add data(info) to card-body
                            if( data[i].categoryPrice > 0 ){
                                var isBundle = true;
                            }else{
                                var isBundle = false;
                            }
                           createCarouselInRow(data,currentDiv,i,isBundle);

                           //call function in AngularJs
                        //    angular.element(document.getElementById("angularScope")).scope().createCarouselInRow(data,currentDiv,i);
                        }else{
                            //add another DIV and insert index(i)
							currentDiv++;
							numberOfMenuPerCarousel += 4;
							//create nth li(indicator) and nth DV1
							createCarousel(currentDiv);
							//then insert index(i) to the current DIV
							createCarouselInRow(data,currentDiv,i);
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
                    //           =              <p class="card-text text-white">Php 59.00</p>
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
<div class="content" id="angularScope" ng-controller="customerPurchase">
    {{-- <div class="alert alert-danger text-center hidden" id="salesErrorDiv"></div> --}}
    <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{-- <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Orders</div>
                    </div> --}}
                    {{-- <div class="content table-responsive table-full-width">
                        <table class="table table-bordered table-striped" >
                            <thead>
                            </thead>
                            <tbody>
                                <tr >
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">All</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Inapoy (Rice)</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Vegetable</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">All Time Fav Meryenda</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Drinks</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Partner El Meryenda</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Main Courses</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Rapsilog</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Partner Meal</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Pizza De Alfredo</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Additional</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Pulotan/Appetizer</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Desserts</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Drinks And Beverages</td>
                                     <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">All Time Fav Shakes</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Buckets</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Noodles and Pasta</td>
                                    <td onclick="displayMenusInCarousel(this)" style="border:1px solid black" class="text-center">Bilao</td>

                                </tr>
                            </tbody>
                        </table>   
                    </div> --}}
                    <div class="btn-group" style="overflow-x:scroll;width:100%" role="group" aria-label="categories" id="categoriesButtons">
                        {{-- <button type="button" class="btn btn-secondary">All</button>
                        <button type="button" class="btn btn-secondary">Inapoy (Rice)</button>
                        <button type="button" ng-click="addButton($event)" class="btn btn-secondary">Vegetable</button>
                        <button type="button" class="btn btn-secondary">All Time Fav Meryenda</button>
                        <button type="button" class="btn btn-secondary">Drinks</button>
                        <button type="button" class="btn btn-secondary">Partner El Meryenda</button>
                        <button type="button" class="btn btn-secondary">Main Courses</button>
                        <button type="button" class="btn btn-secondary">Rapsilog</button>
                        <button type="button" class="btn btn-secondary">Partner Meal</button>
                        <button type="button" class="btn btn-secondary">Pizza De Alfredo</button>
                        <button type="button" class="btn btn-secondary">Additional</button>
                        <button type="button" class="btn btn-secondary">Pulotan/Appetizer</button>
                        <button type="button" class="btn btn-secondary">Desserts</button>
                        <button type="button" class="btn btn-secondary">Drinks And Beverages</button>
                        <button type="button" class="btn btn-secondary">All Time Fav Shakes</button>
                        <button type="button" class="btn btn-secondary">Buckets</button>
                        <button type="button" onclick="displayMenusInCarousel(this)" class="btn btn-secondary">Noodles and Pasta</button>
                        <button type="button" class="btn btn-secondary">Bilao</button> --}}

                    </div>
                </div>
            </div>

        <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 ">
                    <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Category</div>
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
                                {{-- <div class="carousel-item active">
                                        <div class="row" >
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
                                            </div>
                                </div>
                                <div class="carousel-item">
                                        <div class="row" >
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
                                            </div>
                                </div> --}}
                            </div>
                            <a class="carousel-control-prev" style="width:3ch" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" style="width:3ch" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>


                </div>

           
                <div class="col-lg-6 col-md-6  col-sm-6 " >
                    <div class="card">
                            <div class="card-header" style="background-color:slategrey;color:white">Customer Order</div>
                    </div>
                    <div >
                            {!! Form::open(['method'=>'post','id'=>'formSales']) !!}                                
                            <h4 ng-bind="name"></h4>
                            <div class="row">
                                <div class="col-md-4" >                        
                                    {{Form::label('receiptNumber', 'Receipt Number:')}}
                                    {{Form::number('receiptNumber','',['class'=>'form-control','onchange'=>'saveReceiptNumber(this)'])}}
                                </div>
                                {{-- <div class="col-md-3" margin>
                                        {{Form::label('address', 'Address:')}}
                                        {{Form::text('address','',['class'=>'form-control','oninput'=>'enablePrintButton(this)','onchange'=>'saveCustomerAddress(this)'])}}    
                                </div> --}}
                                <div class="col-md-4" margin >
                                    {{Form::label('customerName', 'Customer Name:')}}
                                    {{Form::text('customerName','',['class'=>'form-control','onchange'=>'saveCustomerName(this)'])}}
                                </div>
                                <div class="col-md-4" margin >
                                    {{Form::label('Date', 'Date:')}}
                                    {{-- <input type="date" name="Date" id="today" class="form-control"/>     --}}
                                    {{-- {{\Carbon\Carbon::createFromFormat('d/m/y','25/08/2017')}} --}}
                                    <input type="date" name="Date" id="today" value="" class="form-control"/>    
                                </div>
                            </div>        
                            <br>
                            <div class="row" > 
                                <div class="col-md-12 table-responsive">
                                    <table id="cartTable" class="content table table-striped table-bordered" datatable="ng" dt-options="dtOptions" >
                                        <thead>
                                            <tr>
                                                <th class="text-left" style="color:black">Product</th>
                                                <th class="text-left" style="color:black">Price</th>
                                                <th class="text-left" style="color:black">Quantity</th>
                                                <th class="text-left" style="color:black">Amount</th>
                                                <th class="text-left" style="color:black">Action</th>
                                            </tr> 
            
                                        </thead>
                                        <tbody id="cartTbody">
                                            {{-- <td>Green Tea</td>
                                            <td><input style="width: 100px;" type="number" value="1" class="form-control"></td>
                                            <td>39.00</td>
                                            <td><button class="btn btn-danger">X</button></td>
                                         --}}
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-3">
                                                <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="tableChoices" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Table
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="tableChoices">
                                                            {{-- <button class="dropdown-item" type="button">Table 1</button>
                                                            <button class="dropdown-item" type="button">Table 2</button>
                                                            <button class="dropdown-item" type="button">Table 5</button> --}}
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{-- <textarea style="display:none" name="totalSales" ng-bind="" id="hiddenTotalSales"></textarea> --}}
                                                <input type="hidden" name="totalPrice" id="hiddenTotalPrice">
                                                <label>Total Amount:</label>
                                            </div>
                                            <div class="col-md-3" id="totalSalesDiv">
                                                <p class="form-control" id="totalSales" ng-bind="" style="float: right"></p>
                                            </div>
                                            {{-- <div class="col-md-8 text-right">
                                                    <label>Vat 13%:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="form-control" style="float: right"></p>
                                            </div> --}}
                                            {{-- <div class="col-md-8 text-right">
                                                    <label>Discount:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="form-control" style="float: right"></p>
                                            </div> --}}

                                        </div>
                                        <div class="row justify-content-center">                                           
                                            <div class="col-3">   
                                                <button class="btn btn-primary" onclick="addTotalSalesInForm()" type="submit">Submit</button>
                                                {{-- <button id="printButton" class="btn btn-success" type="button" onclick="printReceipt()" disabled> Print</button> --}}
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
                        <table class="table table-bordered table-striped" style="width:100%" id="manageOrdersDataTable">
                            <thead>
                                <tr>
                                    <th class="text-left">Receipt #</th>
                                    <th class="text-left">Table #</th>
                                    <th class="text-left">Cutomer Name</th>
                                    {{-- <th class="text-left">Total Products</th> --}}
                                    <th class="text-left">Date & Time</th>
                                    <th class="text-left">Payment Status</th>
                                    <th class="text-left">Total Price</th>
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

@section('endBodyScript')
    <script type="text/javascript">
        var ourAngularJsApp = angular.module("ourAngularJsApp", []); 
        
        
        ourAngularJsApp.controller('customerPurchase', ['$scope','$compile','$http',
        function($scope, $compile,$http){
                var _this = this;
            
            //fetch the items in localStorage after the DOM finish
            var len=localStorage.length;
                var thatTbody = document.getElementById("cartTbody");
                document.getElementById("receiptNumber").value = localStorage.getItem("receiptNumber");
                document.getElementById("customerName").value = localStorage.getItem("customerName");

                var totalSalesNgBinds ="";
                for(var i=0; i<len; i++) {
                    var key = localStorage.key(i);
                    var value = localStorage[key];
                    if(value.includes("item")){
                        if( !key.includes("damaged") ){
                            var myItemJSON = JSON.parse(localStorage.getItem(key));            
                            //whenever the price of the product updates, update the price of product in localStorage
                            // var updateTemp = $.parseHTML( myItemJSON.retailPrice );
                            // updateTemp[0].innerHTML=document.getElementById(myItemJSON.itemId).parentNode.previousSibling.innerHTML; //update selling price
                            // updateTemp[1].value=document.getElementById(myItemJSON.itemId).parentNode.previousSibling.innerHTML; //update selling price
                            // console.log(updateTemp[0].outerHTML+updateTemp[1].outerHTML)

                            var newRow = thatTbody.insertRow(-1);
                            newRow.insertCell(-1).innerHTML = myItemJSON.item ;
                            // angular.element( newRow.insertCell(-1) ).append( $compile(updateTemp[0].outerHTML+updateTemp[1].outerHTML)($scope) );
                            angular.element( newRow.insertCell(-1) ).append( $compile(myItemJSON.retailPrice)($scope) );
                            angular.element( newRow.insertCell(-1) ).append( $compile(myItemJSON.quantityPurchase)($scope) );
                            angular.element( newRow.insertCell(-1) ).append( $compile(myItemJSON.salesPrice)($scope) );
                            angular.element( newRow.insertCell(-1) ).append( $compile(myItemJSON.removeButton)($scope) );

                            var temp = document.createElement('div');
                            temp.innerHTML = myItemJSON.salesPrice;  
                            if(totalSalesNgBinds==""){
                                var splitBind = temp.firstChild.getAttribute("ng-bind").split(" ");                                
                                totalSalesNgBinds += splitBind[0];
                            }else{
                                var splitBind = temp.firstChild.getAttribute("ng-bind").split(" ");
                                totalSalesNgBinds += "+" + splitBind[0];
                            }
                        }

                    }
                }

                //initialize totalSales
                document.getElementById("totalSalesDiv").innerHTML="";
                if(totalSalesNgBinds === ""){
                    var price = "<p class='form-control' style='color:green' id='totalSales' ng-bind='" +totalSalesNgBinds+ "'></p>";
                }else{
                    var price = "<p class='form-control' style='color:green' id='totalSales' ng-bind='" +totalSalesNgBinds+ " |number:2'></p>";
                }
                angular.element( totalSalesDiv ).append( $compile(price)($scope) );

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //call function after angularJS loaded
                // angular.element(document).ready(function(){
                //        alert("hi")
                // });

                // $http({
                //     method:"GET",
                //     url:"/admin/orders/getCategoriesInOrders"
                // }).then(function mySuccess(response){
                //     // console.log(response)
                //     var thatDiv = document.getElementById("categoriesButtons");
                //     for(var i = 0; i<response.data.length;i++){
                //         // <button type="button" ng-click="addButton($event)" class="btn btn-secondary">Vegetable</button>
                //         var newButton = document.createElement("button");
                //             // setMultipleAttributes(newButton,{"class":"btn btn-secondary","ng-click":"displayMenusInCarousel($event)"}); 
                //             setMultipleAttributes(newButton,{"class":"btn btn-secondary","onclick":displayMenusInCarousel(response.data[i])}); 
                //             var textNode = document.createTextNode(response.data[i].categoryName);
                //             newButton.appendChild(textNode);
                //             thatDiv.appendChild(newButton);
                //             $compile(angular.element(newButton))($scope);
                //     }
                // }), function myError(response){
                //     console.log("ajax failed.")
                // }

                $scope.$on('$viewContentLoaded',function(){
                    alert("hi");
                });

                $scope.addButton = function(data) {
                    console.log(data)
                    //check if item is already in cartTable
                    var len=localStorage.length;
                    for(var i=0; i<len; i++) {
                        var key = localStorage.key(i);
                        var value = localStorage[key];
                        if(value.includes("item")){
                            var myItemJSON = JSON.parse(localStorage.getItem(key));            
                            if( myItemJSON.item === data.parentNode.firstChild.innerHTML ){
                                alert("Already added.")
                                return;
                            }
                        }
                    
                    }

                    var thatTable = document.getElementById("cartTable");
                    var thatTbody = document.getElementById("cartTbody");
                    var newRow = thatTbody.insertRow(-1);
                    newRow.insertCell(-1).innerHTML = data.parentNode.firstChild.innerHTML;
            
                    var itemName = newRow.cells[0].innerHTML.replace(/\s/g,'').replace(/-/g,'').replace(/\//g,'').replace(/\./g,'').replace(/\+/g,'') ;
                    
                    if( data.getAttribute("data-product_id") == null ){ //if it is bundle
                        var productPrice = "<p class='form-control' style='color:green; width: 100px;'>" +data.previousSibling.innerHTML+ "</p><input type='hidden' name='categoryPrice[]' value='" +data.previousSibling.innerHTML+ "'>";
                        var temp0 = $compile(productPrice)($scope);                
                        angular.element( newRow.insertCell(-1) ).append(temp0);    
                    }else{
                        var productPrice = "<p class='form-control' style='color:green; width: 100px;'>" +data.previousSibling.innerHTML+ "</p><input type='hidden' name='productPrice[]' value='" +data.previousSibling.innerHTML+ "'>";
                        var temp0 = $compile(productPrice)($scope);                
                        angular.element( newRow.insertCell(-1) ).append(temp0);    
                    }

                    if( data.getAttribute("data-product_id") == null ){ //if it is bundle
                        var inputNumber = "<input style='width: 100px;' type='number' ng-init='" +itemName+ " =1' name='categoryQuantity[]' class='form-control' ng-focus='$event = $event' ng-change='changing($event)' ng-model='" +itemName + "' min='1' required></input>";
                        var temp1 = $compile(inputNumber)($scope);
                        angular.element( newRow.insertCell(-1) ).append(temp1);
                    }else{
                        var inputNumber = "<input style='width: 100px;' type='number' ng-init='" +itemName+ " =1' name='quantity[]' class='form-control' ng-focus='$event = $event' ng-change='changing($event)' ng-model='" +itemName + "' min='1' required></input>";
                        var temp1 = $compile(inputNumber)($scope);
                        angular.element( newRow.insertCell(-1) ).append(temp1);

                    }

                    var salesPrice = "<p class='form-control' style='color:green;' ng-init='" +itemName+ "SP=" +data.previousSibling.innerHTML+ "' ng-bind='" +itemName+ "SP |number:2'></p><input  type='hidden' name='salesPrices[]' value=''>";
                    var temp2 = $compile(salesPrice)($scope); 
                    angular.element( newRow.insertCell(-1) ).append(temp2);

                    if( data.getAttribute("data-product_id") == null ){//if it is bundle
                        var removeButton = "<button class='btn btn-danger' data-item-id='" +data.getAttribute("data-category_id")+ "' ng-click='remove($event)'>Remove</button><input type='hidden' name='categoryIds[]' value='"+data.getAttribute("data-category_id")+"'>";
                    }else{
                        var removeButton = "<button class='btn btn-danger' data-item-id='" +data.getAttribute("data-product_id")+ "' ng-click='remove($event)'>Remove</button><input type='hidden' name='productIds[]' value='"+data.getAttribute("data-product_id")+"'><input type='hidden' name='productCategoryIds[]' value='"+data.getAttribute("data-product_category_id")+"'>";
                    }
                
                    var temp3 = $compile(removeButton)($scope);
                    angular.element( newRow.insertCell(-1) ).append(temp3);

                    //store in localStorage
                    var tds  = $(newRow.innerHTML).slice(0);    
                    if( data.getAttribute("data-product_id") == null ){ //if it is bundle
                        var itemObject = {
                            item: tds[0].innerHTML,
                            retailPrice: tds[1].childNodes[0].outerHTML + tds[1].childNodes[1].outerHTML,
                            quantityPurchase: tds[2].firstChild.outerHTML,
                            salesPrice: tds[3].childNodes[0].outerHTML + tds[3].childNodes[1].outerHTML,
                            removeButton: tds[4].childNodes[0].outerHTML + tds[4].childNodes[1].outerHTML ,
                            itemId: data.getAttribute("data-product_id"),
                            action: data.outerHTML};
                    }else{
                        var itemObject = {
                           item: tds[0].innerHTML,
                           retailPrice: tds[1].childNodes[0].outerHTML + tds[1].childNodes[1].outerHTML,
                           quantityPurchase: tds[2].firstChild.outerHTML,
                           salesPrice: tds[3].childNodes[0].outerHTML + tds[3].childNodes[1].outerHTML,
                           removeButton: tds[4].childNodes[0].outerHTML + tds[4].childNodes[1].outerHTML+ tds[4].childNodes[2].outerHTML ,
                           itemId: data.getAttribute("data-product_id"),
                           action: data.outerHTML};
                    }


                        var jsonObject = JSON.stringify(itemObject);
                        localStorage.setItem(tds[0].innerHTML,jsonObject);

                        var totalSalesDiv = document.getElementById("totalSalesDiv");
                        var ngBindAttributes = totalSalesDiv.firstElementChild.getAttribute("ng-bind"); //get ng-bind attribute/s
                        totalSalesDiv.innerHTML =""; 
                        if(ngBindAttributes==""){ //if empty yet
                            var newNgBinds = itemName+"SP";
                        }else{
                            var newNgBinds = ngBindAttributes.split(" ")[0] + "+" + itemName+"SP";
                        }

                        console.log("TScorrect: " + newNgBinds)
                        var price = "<p class='form-control' style='color:green' ng-bind='" +newNgBinds+ " |number:2'></p>";
                        angular.element( totalSalesDiv ).append( $compile(price)($scope) );
                    
                        //insert totalPrice in hidden input name
                        // document.getElementById("hiddenTotalSales").setAttribute("ng-bind",newNgBinds+" |number:2");
                        // <textarea style="display:none" name="totalSales" ng-bind="" id="hiddenTotalSales"></textarea>
                        // var hiddenTotalSales = " <textarea style='display:none' name='totalSales' ng-bind='"+newNgBinds+"'></textarea>";
                        // var temp2 = totalSalesDiv.previousSibling.insertBefore
                        //to be continue...

                };

                $scope.changing = function(event) {
                    var item = JSON.parse(localStorage.getItem(event.currentTarget.parentNode.previousElementSibling.previousElementSibling.innerHTML));
                
                    console.log( ($.parseHTML(item['quantityPurchase'])[0]).getAttribute("ng-model") )
                    var newQuantityPurchase = $($.parseHTML(item['quantityPurchase'])[0]).attr("ng-init",($.parseHTML(item['quantityPurchase'])[0]).getAttribute("ng-model")+"="+event.currentTarget.value);

                    var ngModelName = event.currentTarget.attributes["ng-model"].value;
                    // var oldTs = parseInt(document.getElementById("totalSales").innerText);
                    var retailPrice = parseInt(event.currentTarget.parentNode.previousSibling.innerText);
                    var sellingPrice = ngModelName+"SP";
                    $scope[sellingPrice] =  retailPrice * $scope[ngModelName];
                    // document.getElementById("salesPriceValue").setAttribute("value",retailPrice * $scope[ngModelName]);
                
                    var newSalesPrice = $($.parseHTML(item['salesPrice'])[0]).attr("ng-init", ($.parseHTML(item['quantityPurchase'])[0]).getAttribute("ng-model")+"SP="+ retailPrice * $scope[ngModelName]);


                    
                    //remove
                        localStorage.removeItem(event.currentTarget.parentNode.previousElementSibling.previousElementSibling.innerHTML);
                    //add again
                    var itemObject = {
                        item: item['item'],
                        retailPrice: item['retailPrice'],
                        quantityPurchase: newQuantityPurchase[0].outerHTML,
                        salesPrice: newSalesPrice[0].outerHTML,
                        removeButton: item['removeButton'],
                        itemId: item['itemId'],
                        // purchasePrice: item['purchasePrice'],
                        action: item['action'],
                    };

                    var jsonObject = JSON.stringify(itemObject);
                    localStorage.setItem(item['item'],jsonObject);
                    
                }

                $scope.remove = function(event){
                    
                    var data  = $(event.currentTarget.parentNode.parentNode.innerHTML).slice(0,2);
                    var temp = JSON.parse(localStorage.getItem(data[0].innerHTML));

                    localStorage.removeItem(data[0].innerHTML);
                    event.currentTarget.parentNode.parentNode.remove();                    
                    var thatTable = document.querySelectorAll('#cartTable > tbody > tr')
                    var numberOfRows = thatTable.length;
                    var ngBinds = "";
                    var ngBindsWithoutFormat="";

                    if(numberOfRows > 0){
                        for(var i=0; i < numberOfRows; i++){
                            if(ngBinds==""){
                                ngBinds += thatTable[i].childNodes[3].childNodes[0].getAttribute("ng-bind");
                                ngBindsWithoutFormat += thatTable[i].childNodes[3].childNodes[0].getAttribute("ng-bind").split(" ")[0];
                            }else{
                                ngBinds += " + " + thatTable[i].childNodes[3].childNodes[0].getAttribute("ng-bind");
                                ngBindsWithoutFormat += "+" + thatTable[i].childNodes[3].childNodes[0].getAttribute("ng-bind").split(" ")[0];
                            }
                        }
                        var price = "<p class='form-control' style='color:green' ng-bind='" +ngBindsWithoutFormat+ " |number:2'></p>";
                    }else{
                        var price = "<p class='form-control' style='color:green' ng-bind></p>";
                    }
                    // console.log("ngBinds: " + ngBinds)
                    // console.log("ngBindsWithoutFormat: "+ngBindsWithoutFormat)
                    //update total sales price
                    document.getElementById("totalSalesDiv").innerHTML="";
                    angular.element( totalSalesDiv ).append( $compile(price)($scope) );
                }



                // $scope.createCarouselInRow = function(data,currentDiv,index) {
                //     var newColSm3Div = document.createElement("div");
                //         newColSm3Div.setAttribute("class","col-sm-3");
                //     var newCardBgDark = document.createElement("div");
                //         newCardBgDark.setAttribute("class","card bg-dark");
                //     var newImage = document.createElement("img");
                //         setMultipleAttributes(newImage,{"class":"card-img-top","src":data[index].image});
                //     var newCardBody = document.createElement("div");
                //         newCardBody.setAttribute("class","card-body");
                //     var newH5 = document.createElement("h5");
                //         setMultipleAttributes(newH5,{"class":"card-title text-white"})
                //         var productName = document.createTextNode(data[index].name);
                //         newH5.appendChild(productName);
                //     var newPriceTag = document.createElement("p");
                //         setMultipleAttributes(newPriceTag,{"class":"card-text text-white"});
                //         var price = document.createTextNode(data[index].price);
                //         newPriceTag.appendChild(price);
                //     var newButton = document.createElement("button");
                //         setMultipleAttributes(newButton,{"class":"btn bt-primary","ng-click":"addButton($event)"});
                //         // ng-click="addButton($event)"
                //             $compile(angular.element(newButton))($scope);
                //         var textNode = document.createTextNode("Add");
                //         newButton.appendChild(textNode);
                //         newCardBody.appendChild(newH5);
                //         newCardBody.appendChild(newPriceTag);
                //         newCardBody.appendChild(newButton);
                //     newCardBgDark.appendChild(newImage);
                //     newCardBgDark.appendChild(newCardBody);
                //     newColSm3Div.appendChild(newCardBgDark);
                //     document.getElementById("carouselDiv"+currentDiv).firstChild.appendChild(newColSm3Div);
                    
		
                // };
                


            }
        ]);


    </script>
@endsection
 
@section('modals')
<div id="payModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p></p>
                <h3> <i class="fa fa-exclamation-triangle" style="margin-right: 15px"> </i> Payment </h3>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="text-center">
                <strong>
                    <p>Add informations.</p>
                </strong>
            </div>

            <div class="panel-body">
                <div class="text-center">
                    <div class="form-group clearfix">
                        <button id="payModalContinueButton" type="button" class="btn btn-success">Continue</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewOrderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p></p>
                <h3> <i class="fa fa-exclamation-triangle" style="margin-right: 15px"> </i> Order details </h3>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
 
            <div class="modal-body">
                    <div class="content table-responsive">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-left">Product</th>
                                    <th class="text-left">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="orderDetails">

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