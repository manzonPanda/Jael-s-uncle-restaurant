<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
// use Illuminate\Supoort\Facades\DB;
use DB;
use Datatables;
use App\Product;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        // $this->middleware('auth');

        //specify a guard so it doesn't use the default authentication which is 'web' guard
        $this->middleware('auth:adminGuard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('adminViews.admin-dashboard');
    }

    public function userProfile()
    {
        return view('adminViews.admin-userProfile');
    }

    public function categories()
    {
        return view('adminViews.admin-categories');
    }
    public function getCategories()
    {
        $data = DB::table('categories')
        ->select('categoryName', 'status','category_id');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                $buttons =  "<a href = '#editCategoryModal' data-toggle='modal' >
                    <button onclick='insertDataToEditCategoryModal(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i>Edit</button>
                </a>";
                //0 == INACTIVE && 1 == ACTIVE
                if($data->status === "ACTIVE"){
                    return $buttons."<button id='$data->category_id' onclick='formUpdateCategoryStatus(0" . "," . $data->category_id.")"."' class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i>Disable</button>";
                }else{
                    return $buttons."<button id='$data->category_id' onclick='formUpdateCategoryStatus(1".",".$data->category_id.")'class='btn btn-success'><i class='glyphicon glyphicon-ok'></i>Enable</button>";
                }
            })
         ->make(true);
    }
    public function updateCategoryStatus(Request $request){
        if($request->categoryStatus == 0){
            DB::table('categories')
                ->where('category_id', $request->categoryId)
                ->update(['status' => 'INACTIVE']);
        }else{
            DB::table('categories')
                ->where('category_id', $request->categoryId)
                ->update(['status' => 'ACTIVE']);
        }
        return $request->all();
    }
    public function createCategories(Request $request)
    {
        $this->validate($request,[
            'CategoryName' => 'required',
            'Status' => 'required',

        ]);

        $insertTable = DB::table('categories')->insert(
            ['categoryName' => $request->CategoryName,'status' => $request->Status]
        );

        return "successful";
    }

    public function menus()
    {
        return view('adminViews.admin-menus');
    }
    public function getMenus()
    {
        $data = DB::table('products')
        ->select('image','name','product_id','description','size','price','status');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                $buttons =  "<a href = '#editMenuModal' data-toggle='modal' >
                    <button onclick='insertDataToModal(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> Edit</button>
                </a>";
                
                //0 == INACTIVE && 1 == ACTIVE
                if($data->status === "ACTIVE"){
                    return $buttons."<button id='$data->product_id' onclick='formUpdateProductStatus(0" . "," . $data->product_id.")"."' class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i>Disable</button>";
                }else{
                    return $buttons."<button id='$data->product_id' onclick='formUpdateProductStatus(1".",".$data->product_id.")'class='btn btn-success'><i class='glyphicon glyphicon-ok'></i>Enable</button>";
                }

            })
         ->make(true);
    }
    public function updateProductStatus(Request $request){
        if($request->productStatus == 0){
            DB::table('products')
                ->where('product_id', $request->productId)
                ->update(['status' => 'INACTIVE']);
        }else{
            DB::table('products')
                ->where('product_id', $request->productId)
                ->update(['status' => 'ACTIVE']);
        }
        return $request->all();
    }
    public function getCategoriesDropdown($categorySearch)
    {
        $data = DB::table('categories')
        ->select('categoryName','category_id')
        ->where([['categoryName','LIKE','%'.$categorySearch.'%'],])
        ->orderBy('categoryName','asc')  
        ->limit(5)
        ->get();
        return $data;
    }
    public function getProductsDropdown($productSearch)
    {
        $data = DB::table('products')
        ->select('name','product_id')
        ->where([['name','LIKE','%'.$productSearch.'%'],])
        ->orderBy('name','asc')  
        ->limit(5)
        ->get();
        return $data;
    }
    
    public function getBundleForSpecificCategory($bundleCategorySearch){
        $data = DB::table('prod_categories')
            ->join('products','prod_categories.product_id','=','products.product_id')
            ->join('categories','prod_categories.category_id','=','categories.category_id')
            ->where('prod_categories.category_id','=',$bundleCategorySearch)
            ->select('products.name','categories.price','products.product_id','prod_categories.quantity')
            ->get();
        return $data;
    }

    public function editProduct(Request $request){

        DB::table('products')
        ->where( 'product_id',$request->productId)
        ->update( ['name'=>$request->productName,'description'=>$request->productDescription,'size'=>$request->productSize,'price'=>$request->productPrice] );

    
    }

    public function createProduct(Request $request){
        $this->validate($request,[
            'ProductName' => 'required',
            // 'Price' => 'required',
            // 'Description' => 'required',
            'Category' => 'required',
            // 'product_id' => 'required',
            'Status' => 'required',
        ]);
        
        // http://jael.com/assets/img/default.jpg     this is image path
        $insertProduct = DB::table('products')->insert(
            ['image' => 'http://jael.com/assets/img/default.jpg', 'name' => $request->ProductName, 'price' => $request->Price,'description' => $request->Description,'status' => $request->Status,'size'=>$request->Size,'created_at' => $request->Date]
        );

        // $prod_id = DB::table('products')
        // ->where('name','=',$request->ProductName)->value('product_id');
        $prod_id = DB::table('products')
        ->select('product_id')
        ->where('name','=',$request->ProductName)
        ->orderBy('product_id','desc')  
        ->limit(1)
        ->get();
 

        //insert to prod_categories
        $insertProductCategories = DB::table('prod_categories')->insert(
            ['product_id' => $prod_id[0]->product_id, 'category_id'=>$request->category_id,'quantity'=>'1']
        );

        return "successful";
    }
    public function createBundle(Request $request){
        $this->validate($request,[
            'Category' => 'required',
            'Price' => 'required',
            // 'product_id' => 'required',
        ]);
            //update price column in specific category
            // DB::table('Categories')
            // ->where( 'category_id',$request->Category )
            // ->update(['price'=>$request->Price]);
            
            //update if exist, crete if not exist
            DB::table('Categories')
            ->updateOrInsert(  
                ['category_id'=>$request->category_id],
                ['price'=>$request->Price]
            );
            
            //add products in other categories 
            //delete first all products(rows) in specific bundle
            DB::table('prod_categories')
            ->where('category_id','=', $request->category_id)
            ->delete();
            
            $arrayCount = count($request->product_id);
            for($i = 0;$i<$arrayCount;$i++){
                //insert to prod_categories
                DB::table('prod_categories')->insert(
                    ['product_id' => $request->product_id[$i], 'category_id'=>$request->category_id,'quantity' => $request->quantity[$i]]
                );
            }

            return "successful";
    }

    public function orders()
    {
        return view('adminViews.admin-orders');
    }
    
    public function getCategoriesInOrders()
    {
        $data = DB::table('categories')
        ->select('categoryName','category_id')
        ->orderBy('categoryName','asc')
        ->get();
        return $data;
    
    }
    public function getTablesInOrders()
    {
        $data = DB::table('tables')
        ->select('tableName','capacity','tableId')
        ->orderBy('tableName','asc')
        ->get();
        return $data;
    
    }
    public function getMenusToCarousel($category)
    {
        // $data = DB::table('products')
        //     ->select('image', 'name','price')
        //     ->where('categoryName','=',$category)
        //     ->get();
        // return $data;
        ///////////////////////// working//////////////////////////////////////
        $data = DB::table('prod_categories')
        ->join('products','prod_categories.product_id','=','products.product_id')
        ->join('categories','prod_categories.category_id','=','categories.category_id')
        ->select('products.name','categories.price as categoryPrice','prod_categories.quantity as categoryQuantity','products.price','products.product_id','products.image','products.size','prod_categories.category_id as categoryId','categories.categoryName')
        ->where('prod_categories.category_id','=',$category)
        // ->toSql();
        ->get();
        
        // $data = DB::table('prod_categories')
        // ->join('products','prod_categories.product_id','=','products.product_id')
        // ->join('categories','prod_categories.category_id','=','categories.category_id')
        // ->select('products.name','categories.price as categoryPrice','products.price','products.product_id','products.image','products.size','prod_categories.category_id as catergoryId' )
        // ->groupBy('categoryPrice')
        // ->having('prod_categories.category_id','=',$category)
        // ->toSql();
        // ->get();

        //if category(categoryPrice) has a price, then it is a bundle
        // $data[0]->categories.price
        return $data;


//     $data = DB::table('prod_categories')
//     ->join('products','prod_categories.product_id','=','products.product_id')
//     ->join('categories','prod_categories.category_id','=','categories.category_id')
//     ->where('prod_categories.category_id','=',$bundleCategorySearch)
//     ->select('products.name','categories.price','products.product_id','prod_categories.quantity')
//     ->get();
// return $data;
    }

    public function createOrder(Request $request){
        $this->validate($request,[
            // 'ProductName' => 'required',
            // 'Price' => 'required',
            // 'Description' => 'required',
            'tableId' => 'required',
        ]);

        if( $request->categoryIds ){
            $countBundle = count($request->categoryIds);
        }
        if( $request->productIds ){
            $countProducts = count($request->productIds);
        }

        $data = DB::table('transactions')
            ->select('transaction_id')
            ->where('transaction_id' , '=' , $request->receiptNumber)
            ->get();
            
            
            if($data->isEmpty()){
                $insert = DB::table('transactions')->insert(
                    ['transaction_id' => $request->receiptNumber, 'tableId' => $request->tableId, 'customer_name' => $request->customerName, 'status' => 'PENDING','created_at' => $request->Date,'totalPrice' =>$request->totalPrice]
                );

                //for bundles
                if( $request->categoryIds ){ //if there is bundle added in cart
                    for($i = 0; $i<$countBundle; $i++){
                        $productsOfBundle = DB::table('prod_categories')    
                        ->select('product_id','quantity','category_id')
                        ->where('category_id' , '=' , $request->categoryIds[$i])
                        ->get();
                        for( $j = 0; $j < count($productsOfBundle); $j++ ){ //insert all products of bundle in table
                            $insert = DB::table('prod_transact')->insert(
                                ['transaction_id' => $request->receiptNumber, 'product_id' => $productsOfBundle[$j]->product_id, 'category_id' => $request->categoryIds[$i], 'quantity' => $productsOfBundle[$j]->quantity,'price' => $request->categoryPrice[$i],'created_at' => $request->Date]);
                            }
                            
                        }
                    }
                    
                if( $request->productIds ){ //if there is product added in cart
                    //for products
                    for($i = 0; $i<$countProducts; $i++){
                        $insert = DB::table('prod_transact')->insert(
                            ['transaction_id' => $request->receiptNumber, 'product_id' => $request->productIds[$i],'category_id' => $request->productCategoryIds[$i],'quantity' => $request->quantity[$i],'price' => $request->productPrice[$i],'created_at' => $request->Date]);

                    }
                }
            }

        return "100% success!";
    }
    // public function getCategoriesDropdown($categorySearch)
    // {
    //     $data = DB::table('categories')
    //     ->select('categoryName','category_id')
    //     ->where([['categoryName','LIKE','%'.$categorySearch.'%'],])
    //     ->orderBy('categoryName','asc')  
    //     ->limit(5)
    //     ->get();
    //     return $data;
    // }

    public function customerWillPay($transactionId){
        return "continue to update transaction table query";
    }
    public function getManageOrders()
    {
        $data = DB::table('transactions')
        ->select('transaction_id','tableId','customer_name','created_at','status','totalPrice')
        ->where('status','=','PENDING');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                return "
                <a href = '#editOrderModal' data-toggle='modal' >
                    <button onclick='editOrder(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i>Edit</button>
                </a>
                <a href = '#viewOrderModal' data-toggle='modal' >
                    <button onclick='viewOrderModal(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-eye'></i>View</button>
                </a>
                <a href = '#payModal' data-toggle='modal' >
                    <button data-transaction_id='$data->transaction_id'class='btn btn-success' onclick='insertTransactionIdInButton(this)' ><i class='glyphicon glyphicon-th-list'></i>Pay</button>
                </a>
                ";
            })
         ->make(true);
    }

    public function tables()
    {
        return view('adminViews.admin-tables');
    }
    
    public function createTable(Request $request)
    {
        $this->validate($request,[
            'TableName' => 'required',
            'Capacity' => 'required',
            'Status' => 'required',

        ]);

        $insertTable = DB::table('tables')->insert(
            ['tableName' => $request->TableName, 'capacity' => $request->Capacity, 'status' => $request->Status]
        );

        return "successful";
    }
    
    public function getManageTables()
    {
        $data = DB::table('tables')
        ->select('tableName','capacity','status');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                return "
                <a href = '#editTableModal' data-toggle='modal' >
                    <button class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> Edit</button>
                </a>
                <a href = '#deleteTableModal' data-toggle='modal' >
                    <button onclick='deleteTable(this)'class='btn btn-danger' ><i class='glyphicon glyphicon-th-list'></i> Del</button>
                </a>
                ";
            })
         ->make(true);
    }
        

    
    public function reports()
    {
        return view('adminViews.admin-reports');
    }

    public function settings()
    {
        return view('adminViews.admin-settings');
    }
    public function aboutUs()
    {
        return view('adminViews.admin-aboutUs');
    }
}
