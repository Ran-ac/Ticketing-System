<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\TicketController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/index', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('admin.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





//Users
Route::get('/admin/user/index', [UserController::class, 'index'])->name('admin.user.index');

Route::get('/admin/user/fetchuserData', [UserController::class, 'fetchUserData'])->name('admin.user.fetchUserData');

Route::get('admin/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('admin/user/store', [UserController::class, 'store'])->name('user.store');

Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/admin/user/update', [UserController::class, 'update'])->name('user.update');

Route::delete('/admin/user/delete-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');



//GAOC clinic
Route::get('/admin/clinics/gaoc/index', [ClinicController::class, 'gaocIndex'])->name('admin.clinics.gaoc.index');

Route::get('/clinics/gaoc', [ClinicController::class, 'fetchGaocClinicsData'])->name('admin.clinics.gaoc.fetchGaocClinicsData');

//Novodental clinic

Route::get('/admin/clinics/novodental', [ClinicController::class, 'novodentalIndex'])->name('admin.clinics.novodental.index');

Route::get('/clinics/novodental', [ClinicController::class, 'fetchNovodentalClinicsData'])->name('admin.clinics.novo.fetchNovoClinicsData');


//JentleDerm clinic

Route::get('/admin/clinics/jentlederm/index', [ClinicController::class, 'JentleDermIndex'])->name('admin.clinics.jentlederm.index');

Route::get('/clinics/jentlederm', [ClinicController::class, 'fetchJentleDermClinicsData'])->name('admin.clinics.jentlederm.fetchJentleDermClinicsData');


//clinic 
Route::get('admin/clinics/create', [ClinicController::class, 'create'])->name('clinic.create');

Route::post('admin/clinics/store', [ClinicController::class, 'store'])->name('clinic.store');

Route::get('/admin/clinic/edit/{id}', [ClinicController::class, 'edit'])->name('clinic.edit');
Route::put('/admin/clinic/update', [ClinicController::class, 'update'])->name('clinic.update');

Route::delete('/admin/clinic/delete-clinic/{id}', [ClinicController::class, 'destroy'])->name('clinic.destroy');


//clinics gaoc
Route::get('/admin/clinics/novodental/index', [ClinicController::class, 'index'])->name('admin.clinics.novodental.index');


//ticket 

Route::get('/admin/ticket/index', [TicketController::class, 'index'])->name('admin.ticket.index');
Route::get('/admin/ticket/fetchTicketData', [TicketController::class, 'fetchTicketData'])->name('admin.ticket.fetchTicketData');

Route::get('admin/ticket/create', [TicketController::class, 'create'])->name('admin.ticket.create');
Route::post('admin/ticket/store', [TicketController::class, 'store'])->name('admin.ticket.store');

Route::get('/admin/ticket/edit/{id}', [TicketController::class, 'edit'])->name('admin.ticket.edit');
Route::put('/admin/ticket/update', [TicketController::class, 'update'])->name('admin.ticket.update');

Route::delete('/admin/ticket/delete-ticket/{id}', [TicketController::class, 'destroy'])->name('admin.ticket.destroy');



require __DIR__.'/auth.php';
