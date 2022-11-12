<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware(['guest:sanctum'])->group(function () {
    //password reset
//    Route::post('/forgot-password', [NewPasswordController::class, 'forgotPassword']);
//    Route::post('/reset-password', [NewPasswordController::class, 'reset']);
    //register
    Route::post('/register',[\App\Http\Controllers\Auth\RegisterController::class,'store']);
    //login
    Route::post('/login',[\App\Http\Controllers\Auth\LoginController::class,'store']);



});
Route::middleware(['auth:sanctum'])->group(function () {
    //logout routes
    Route::get('show/connected/device',[\App\Http\Controllers\Auth\LogOutController::class,'index']);//les appariels connecter
    Route::delete('logout',[\App\Http\Controllers\Auth\LogOutController::class,'destroyFromCurrent']);//logout from current device
    Route::delete('logout/{id}',[\App\Http\Controllers\Auth\LogOutController::class,'destroyFromOne']);//logout from current device
    Route::delete('logout/all',[\App\Http\Controllers\Auth\LogOutController::class,'destroyFromAll']);//logout from all device


//    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend']);//email verification send
//    //super admin route
    //manage users
        //index get the data, store to add user, show to search by city or specialite, update to validate a new doctor and delete to delete an account !

    Route::get('/owner/acceuil',[App\Http\Controllers\Owner\UserController::class, 'acceuil']);

    Route::post('/doctors/search',[App\Http\Controllers\Owner\UserController::class, 'searchDoctors']);
    Route::get('/demendes',[App\Http\Controllers\Owner\UserController::class, 'demandes']);
    //manage roles and permessions and users
    Route::apiResource('roles','App\Http\Controllers\Owner\RoleController');
    Route::apiResource('permissions','App\Http\Controllers\Owner\PermissionController');
    Route::apiResource('doctors', 'App\Http\Controllers\Owner\UserController');
    //manage profil
    Route::get('/profil',[App\Http\Controllers\profil\ProfilController::class, 'index']);
    Route::put('/profil/password',[App\Http\Controllers\profil\PasswordController::class, 'store']);
    Route::put('/profil/informations',[App\Http\Controllers\profil\InformationController::class, 'store_1']);
    Route::put('/profil/informations/profissionels',[App\Http\Controllers\profil\InformationController::class, 'store_2'])->middleware('role:doctor');
    Route::post('/profil/photo',[App\Http\Controllers\profil\PhotoController::class, 'store']);
    Route::delete('/profil/photo',[App\Http\Controllers\profil\PhotoController::class, 'destroy']);
    //patient route
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/acceuil',[App\Http\Controllers\Patient\AcceuilController::class, 'acceuil']);
        Route::post('/medcin/search',[App\Http\Controllers\Patient\SearchController::class, 'Doctors']);
        Route::get('/medcin/show/{id}',[App\Http\Controllers\Patient\ShowDoctorController::class, 'show']);
        Route::apiResource('/patient/Rendez_Vous', 'App\Http\Controllers\Patient\ReservationController');
    });
    //doctor route
    Route::middleware(['role:doctor'])->group(function () {
        Route::apiResource('/doctor/calendar', 'App\Http\Controllers\Doctor\Calendar\ManageTimeController');
        Route::put('/doctor/calendar',[App\Http\Controllers\Doctor\Calendar\ManageTimeController::class, 'update']);
        Route::get('/reservations',[App\Http\Controllers\Doctor\ReservationController::class, 'index']);
        Route::post('/reservations/search',[App\Http\Controllers\Doctor\ReservationController::class, 'search']);
        Route::post('/reservations/validation/{id}',[App\Http\Controllers\Doctor\ReservationController::class, 'update']);
        Route::get('/calendar',[App\Http\Controllers\Doctor\ReservationController::class, 'eventOnCalendar']);

    });
});
//
//Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
//    ->middleware(['signed']);


