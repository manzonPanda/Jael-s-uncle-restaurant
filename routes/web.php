<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//where is ->name('login') routes ?

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('admin/login','Auth\AdminLoginController@login')->name('admin.login.submit');
Route::get('/admin', 'AdminController@index')->name('admin.dashboard');
Route::get('/admin/userProfile', 'AdminController@userProfile')->name('admin.userProfile');

Route::get('/admin/categories', 'AdminController@categories')->name('admin.categories');
Route::get('/admin/categories/getCategories', 'AdminController@getCategories')->name('categories.getCategories');

Route::get('/admin/menus', 'AdminController@menus')->name('admin.menus');
Route::get('/admin/menus/getMenus', 'AdminController@getMenus')->name('menus.getMenus');
Route::Post('admin/menus/createProduct', 'AdminController@createProduct')->name('admin.createProduct');

Route::get('/admin/orders', 'AdminController@orders')->name('admin.orders');
Route::get('/admin/orders/getManageOrders', 'AdminController@getManageOrders')->name('orders.getManageOrders');
Route::Get('/admin/orders/getMenusToCarousel/{category}', 'AdminController@getMenusToCarousel');
// Route::get('admin/orders/getMenusToCarousel', 'AdminController@getMenusToCarousel')->name('orders.getMenusToCarousel');

Route::get('/admin/tables', 'AdminController@tables')->name('admin.tables');
Route::get('/admin/tables/getManageTables', 'AdminController@getManageTables')->name('tables.getManageTables');
Route::Post('admin/menus/createTable', 'AdminController@createTable')->name('admin.createTable');

Route::get('/admin/reports', 'AdminController@reports')->name('admin.reports');
Route::get('/admin/aboutUs', 'AdminController@aboutUs')->name('admin.aboutUs');
Route::get('/admin/settings', 'AdminController@settings')->name('admin.settings');
