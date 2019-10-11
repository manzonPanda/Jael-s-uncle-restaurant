<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
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

    public function menus()
    {
        return view('adminViews.admin-menus');
    }
    public function getMenus()
    {
        $data = DB::table('products')
        ->select('image','name','price','category','status');
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
    public function createProduct(Request $request){
        $this->validate($request,[
            'ProductName' => 'required',
            'Price' => 'required',
            'Description' => 'required',
            'Category' => 'required',
            // 'product_id' => 'required',
            'Status' => 'required',
        ]);

        $insertProduct = DB::table('products')->insert(
            ['image' => $request->ProductName, 'name' => $request->ProductName, 'price' => $request->Price,'decription' => $request->Description,'category' => $request->Category,'status' => $request->Status]
        );

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
