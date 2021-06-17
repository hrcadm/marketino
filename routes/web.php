<?php

use Illuminate\Support\Facades\Route;

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

/* PUBLIC ROUTES */
Route::group(
    [
        'namespace' => 'App\Http\Controllers',
    ],
    function () {
        Route::redirect('/', 'customers');

        Route::post('webhooks/support_email', 'WebhookController@supportEmail')
             ->withoutMiddleware(
                 [
                     App\Http\Middleware\VerifyCsrfToken::class,
                 ]
             );
    }
);

/* AUTHENTICATED ROUTES */
Route::group(
    [
        'namespace'  => 'App\Http',
        'middleware' => ['auth:sanctum', 'verified'],
    ],


    function () {
        Route::view('dashboard', 'pages.dashboard')->name('dashboard');

        Route::get('quotes/send-to-email/{id}', ['uses' => 'Controllers\QuoteController@sendToEmail'])->name('sendToEmail');
        Route::get('quotes/download-quote-pdf/{id}', ['uses' => 'Controllers\QuoteController@downloadPdf'])->name('downloadPdf');
        Route::post('quotes/{customer_id}', ['uses' => 'Controllers\QuoteController@newQuote'])->name('newQuote');
        Route::get('quotes/send-grenke-email/{customer?}', ['uses' => 'Controllers\QuoteController@sendGrenkeEmail'])->name('sendGrenkeEmail');

        Route::resource('next-steps', 'Controllers\NextStepController')->except('create', 'show', 'edit', 'destroy');

        Route::get('customers/create', 'Controllers\CustomerController@create')->name('customers.create');
        Route::get('customers/{customer}/{tab?}', 'Controllers\CustomerController@show')->name('customers.show');
        Route::resource('customers', 'Controllers\CustomerController')->except('show');

        Route::resource('tickets', 'Controllers\TicketController');
        Route::get('tickets/{ticket}/attachment', 'Controllers\TicketAttachmentsController@show')->name('ticket.attachment.show');

        Route::group(['prefix' => 'billing'], function () {
            Route::get('quotes', 'Controllers\BillingController@quotesIndex')->name('billing.quotes');
        });
        Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
            Route::get('/', 'Controllers\SettingController@index')->name('index');
            Route::resource('users', 'Controllers\UserController')->except('show');
        });
        
    }
);
