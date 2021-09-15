<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoiceStatusController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
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
})->middleware('guest');

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('active');

    Route::resource('invoices', InvoiceController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('products', ProductController::class);
    Route::resource('statuses', InvoiceStatusController::class);
    //invoices details and attachments
    Route::resource('invoice_details', InvoiceDetailsController::class);
    Route::resource('invoice_attachments', InvoiceAttachmentsController::class);
    Route::get('download/{invoice}/{attachment}', [InvoiceAttachmentsController::class, 'download']);

    // change invoice payment status
    Route::get('invoice-status/{invoice_id}', [InvoiceStatusController::class, 'invoice_status'])->name('invoice-status');
    Route::post('change-invoice-status', [InvoiceStatusController::class, 'change_invoice_status'])->name('change-invoice-status');

    // get invoices by status
    Route::get('paid-invoices', [InvoiceController::class, 'getPaid'])->name('paid-invoices');
    Route::get('unpaid-invoices', [InvoiceController::class, 'getUnpaid'])->name('unpaid-invoices');
    Route::get('partially-paid-invoices', [InvoiceController::class, 'getPartiallyPaid'])->name('partially-paid-invoices');

    // archive invoices
    Route::post('archive-invoices', [InvoiceController::class, 'archive'])->name('archive-invoices');
    Route::post('restore-invoices', [InvoiceController::class, 'restore'])->name('restore-invoices');
    Route::get('archived-invoices', [InvoiceController::class, 'getArchived'])->name('archived-invoices');

    //export invoices as excel
    Route::get('invoices-export', [InvoiceController::class, 'export'])->name('export-excel');

    Route::get('/section/{id}', [InvoiceController::class, 'getProducts']);

    //reports

    // invoices
    Route::get('invoices-reports', [ReportController::class, 'invoices'])->name('invoices-reports');
    Route::post('search-invoices', [ReportController::class, 'searchInvoices'])->name('search-invoices');
    // mark all notifications as read for current user
    Route::get('all-as-read', [InvoiceController::class, 'mark_all_as_read'])->name('all-as-read');
    //mark a notification as read
    Route::get('read-notification/{id}', [InvoiceController::class, 'read_notification'])->name('read-notofication');

    // sections
    Route::get('customers-reports', [ReportController::class, 'customers'])->name('customers-reports');
    Route::post('search-customers', [ReportController::class, 'searchCustomers'])->name('search-customers');

    });


Route::get('/{page}', [AdminController::class, "index"]);
