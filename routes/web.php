<?php

use App\Http\Controllers\notificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Categorycontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Eventcontroller;
use App\Http\Controllers\Golf_club_Controller;
use App\Http\Controllers\GolfController;
use App\Http\Controllers\Organizercontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SyncerController;
use App\Http\Controllers\Vanuecontroller;
use App\Http\Controllers\WordPressController;
use App\Http\Controllers\LogController;

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

Auth::routes();

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/password', [AuthController::class, 'password'])->name('password');

// Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
// Route::post('/login', [AuthController::class, 'authenticate']);
// Route::get('/register', [AuthController::class, 'register']);
Route::post('/reset-update-password', [AuthController::class, 'resetPassword'])->name('password.reset.update');
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Route::get('/forget-password', [AuthController::class, 'forget_password_index'])->name('forget-password');


Route::middleware(['Authmiddleware'])->group(function () {





    // route dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashbord')->middleware('auth');
    Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

    //route barang
// Route::resource('/barang', BarangController::class)->middleware('auth');

    // route add golf-group
    Route::get('/golf-groups', [GolfController::class, 'index'])->defaults('_config', [
        'view' => 'golf_group.golf'
    ])->name('golf-group');

    Route::get('/golf-group/create', [GolfController::class, 'create'])->defaults('_config', [
    ])->name('golf-group-adds');

    Route::post('/golf-group/add', [GolfController::class, 'store'])->name('golf-group-add');

    Route::get('group_edit/{id}', [GolfController::class, 'edit'])->name('group_edit');

    route::post('/golf-group-update', [GolfController::class, 'update'])->name('golf-group-updates');

    Route::delete('/golf-group-delete/{id}', [GolfController::class, 'destroy'])->name('golf-group-delete');


    // route add golf-club

    Route::get('/golf-club', [Golf_club_Controller::class, 'index'])->defaults('_config', [
        'view' => 'golf_club.golf-club'
    ])->name('golf-club');

    Route::get('/golf-club/create', [Golf_club_Controller::class, 'create'])->defaults('_config', [
    ])->name('golf-club-adds');

    Route::post('/golf-club/add', [Golf_club_Controller::class, 'store'])->name('golf-club-insert');



    Route::get('golf-club/edit/{id}', [Golf_club_Controller::class, 'edits'])->defaults('_config', [
        'view' => 'golf_group.golf'
    ])->name('golf_club.edit');

    route::post('/golf-club-update', [Golf_club_Controller::class, 'update'])->name('golf-club-updates');


    route::delete('/golf_club_delete/{id}', [Golf_club_Controller::class, 'destroy'])->name('golf_club_delete');

    // add Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/synchistory', [SyncerController::class, 'getUserHistory']);

    route::get('/user/create', [UserController::class, 'create'])->name('create_user');

    route::post('/user/add', [UserController::class, 'store'])->name('insert_user');

    route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user_edit');

    route::post('/user/update', [UserController::class, 'update'])->name('update_user');

    route::delete('/user_delete/{id}', [UserController::class, 'destroy'])->name('delete_user');



    // add wordpress user

    Route::get('/wordpress-users', [WordPressController::class, 'index'])->name('wordpress_user');
    // Route::get('/', [SyncerController::class, 'getUserHistory']);

    route::get('/wordpress-user/create', [WordPressController::class, 'create'])->name('wordpress_create_user');

    route::post('/wordpress-user/add', [WordPressController::class, 'store'])->name('insert_wordpress_user');

    route::get('/wordpress-user/edit/{id}', [WordPressController::class, 'edit'])->name('wordpress_user_edit');

    route::post('/wordpress-user/update', [WordPressController::class, 'update'])->name('wordpress_update_user');

    route::delete('/wordpress-user/{id}', [WordPressController::class, 'destroy'])->name('wordpress_delete_user');


    // add vanue

    Route::get('/vanue', [Vanuecontroller::class, 'index'])->name('vanue');

    route::get('/vanue/create', [Vanuecontroller::class, 'create'])->name('create_vanue');

    route::post('/vanue/add', [Vanuecontroller::class, 'store'])->name('insert_vanue');

    route::get('/vanue/edit/{id}', [Vanuecontroller::class, 'edit'])->name('vanue_edit');

    route::post('/vanue-update', [Vanuecontroller::class, 'update'])->name('vanue-updates');

    route::delete('/vanue-delete/{id}', [Vanuecontroller::class, 'destroy'])->name('vanue-delete');


    // add organizer

    Route::get('/organizer', [Organizercontroller::class, 'index'])->name('organizer');

    route::get('/organizer/create', [Organizercontroller::class, 'create'])->name('create_organizer');

    route::post('/organizer/add', [Organizercontroller::class, 'store'])->name('insert_organizer');

    route::get('/organizer/edit/{id}', [Organizercontroller::class, 'edit'])->name('organizer_edit');

    route::post('/organizer-update', [Organizercontroller::class, 'update'])->name('organizer-updates');

    route::delete('/organizer-delete/{id}', [Organizercontroller::class, 'destroy'])->name('organizer-delete');

    // add category 

    Route::get('/category', [Categorycontroller::class, 'index'])->name('category');

    route::get('/category/create', [Categorycontroller::class, 'create'])->name('create_category');

    route::post('/category/add', [Categorycontroller::class, 'store'])->name('insert_category');

    route::get('/category/edit/{id}', [Categorycontroller::class, 'edit'])->name('category_edit');

    route::post('/category-update', [Categorycontroller::class, 'update'])->name('category-updates');

    route::delete('/category-delete/{id}', [Categorycontroller::class, 'destroy'])->name('category-delete');

    // add Events

    Route::get('/event', [Eventcontroller::class, 'index'])->name('event');

    route::get('/event/create', [Eventcontroller::class, 'create'])->name('create_event');

    route::post('/event/add', [Eventcontroller::class, 'store'])->name('insert_event');

    route::get('/event/edit/{id}', [Eventcontroller::class, 'edit'])->name('event_edit');

    route::post('/event-update', [Eventcontroller::class, 'update'])->name('event-updates');

    route::delete('/event-delete/{id}', [Eventcontroller::class, 'destroy'])->name('event-delete');



    //log 
    Route::get('/logs', [LogController::class, 'index'])->name('log');


    // notification bell icon
    // Route::get('/notification', [notificationController::class, 'index'])->name('notification');

    // Route::post('/update-notification', [notificationController::class, 'update'])->name('update-notification');





    // middleware close
});
