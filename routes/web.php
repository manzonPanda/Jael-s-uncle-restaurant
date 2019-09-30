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
Route::get('/admin/menus', 'AdminController@menus')->name('admin.menus');
Route::get('/admin/reports', 'AdminController@reports')->name('admin.reports');
Route::get('/admin/aboutUs', 'AdminController@aboutUs')->name('admin.aboutUs');
Route::get('/admin/settings', 'AdminController@settings')->name('admin.settings');