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
        ->select('categoryName', 'status');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                return "
                <a href = '#editCategoryModal' data-toggle='modal' >
                    <button onclick='editCategory(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> View</button>
                </a>
                ";
            })
         ->make(true);
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
        ->select('image','name','description','size','price','status');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                return "
                <a href = '#editMenuModal' data-toggle='modal' >
                    <button onclick='editMenu(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> Edit</button>
                </a>
                ";
            })
         ->make(true);
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
    public function createProduct(Request $request){
        $this->validate($request,[
            'ProductName' => 'required',
            // 'Price' => 'required',
            // 'Description' => 'required',
            'Category' => 'required',
            // 'product_id' => 'required',
            'Status' => 'required',
        ]);
        
        // http://jael.com/assets/img/icedCoffee.jpg     this is image path
        $insertProduct = DB::table('products')->insert(
            ['image' => $request->ProductName, 'name' => $request->ProductName, 'price' => $request->Price,'description' => $request->Description,'status' => $request->Status,'size'=>$request->Size]
        );

        $prod_id = DB::table('products')->where('name','=',$request->ProductName)->value('product_id');

        //insert to prod_categories
        $insertProductCategories = DB::table('prod_categories')->insert(
            ['product_id' => $prod_id, 'category_id'=>$request->category_id]
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
    public function getMenusToCarousel($category)
    {
        $data = DB::table('products')
            ->select('image', 'name','price')
            ->where('category','=',$category)
            ->get();
        return $data;
    }
    public function getManageOrders()
    {
        $data = DB::table('orders')
        ->select('receiptNumber','product_id','created_at','status');
        return Datatables::of($data)
            ->addColumn('action',function($data){
                return "
                <a href = '#editOrderModal' data-toggle='modal' >
                    <button onclick='editOrder(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> View</button>
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
