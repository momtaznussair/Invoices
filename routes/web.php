<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoiceStatusController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('invoices', InvoiceController::class);
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);
Route::resource('statuses', InvoiceStatusController::class);

Route::resource('invoice_details', InvoiceDetailsController::class);
Route::resource('invoice_attachments', InvoiceAttachmentsController::class);
// change invoice payment status
Route::get('invoice-status/{invoice_id}', [InvoiceStatusController::class, 'invoice_status'])->name('invoice-status');
Route::post('change-invoice-status', [InvoiceStatusController::class, 'change_invoice_status'])->name('change-invoice-status');

// get invoices by status
Route::get('paid-invoices', [InvoiceController::class, 'getPaid'])->name('paid-invoices');
Route::get('unpaid-invoices', [InvoiceController::class, 'getUnpaid'])->name('unpaid-invoices');
Route::get('partially-paid-invoices', [InvoiceController::class, 'getPartiallyPaid'])->name('partially-paid-invoices');


Route::get('download/{invoice}/{attachment}', [InvoiceAttachmentsController::class, 'download']);

Route::get('/section/{id}', [InvoiceController::class, 'getProducts']);



Route::get('/{page}', [AdminController::class, "index"]);
