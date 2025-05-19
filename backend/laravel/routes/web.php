<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/payment', [PaymentController::class, 'create']);
Route::get('/payment/status/{transactionId}', [PaymentController::class, 'checkStatus']);


Route::get('/', function () {
    return view('welcome');
});
