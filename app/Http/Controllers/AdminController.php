<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use DB;
use Datatables;


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
                <a href = '#purchasesModal' data-toggle='modal' >
                    <button onclick='getItems(this)'class='btn btn-info' ><i class='glyphicon glyphicon-th-list'></i> View</button>
                </a>
                ";
            })
         ->make(true);
    }

    public function menus()
    {
        return view('adminViews.admin-menus');
    }
    public function orders()
    {
        return view('adminViews.admin-orders');
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
