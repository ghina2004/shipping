<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Shipment\ShipmentAnswerController;
use App\Http\Controllers\Shipment\ShipmentController;
use App\Http\Controllers\ShipmentFullController;
use App\Http\Controllers\supplier\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['locale'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
        });

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::post('forgetPassword', 'forgetPassword')->name('forget.password');
            Route::post('resetPassword/{userId}', 'resetPassword')->name('reset.password');
        });

        Route::controller(VerificationController::class)->group(function () {
            Route::post('verifyAuthCode/{userId}', 'verifyAuthCode')->name('verify.auth.code');
            Route::post('verifyPasswordCode/{userId}', 'verifyPasswordCode')->name('verify.password.code');
            Route::get('refreshCode/{userId}', 'refreshCode')->name('refresh.code');
        });

        Route::middleware(['auth:sanctum'])->controller(AuthController::class)->group(function () {
            Route::get('logout', 'logout')->name('logout');
        });
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('order')->controller(OrderController::class)->group(function () {
            Route::get('/employee', 'showEmployeeorders');
            Route::get('/{orderId}', 'showorder');
            Route::get('/shipments/{orderId}', 'showShipmentsorder');
        });

        Route::prefix('categories')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}','show');
            Route::get('/{id}/with-questions','showWithQuestions');
            Route::post('/','store');
            Route::post('/update/{id}','update');
            Route::delete('/{id}','destroy');
        });

        Route::prefix('shipment')->controller(ShipmentController::class)->group(function () {
            Route::post('/', 'store');//->middleware('permission:create.shipment');
            Route::get('/{shipmentId}', 'show');
            Route::post('/update/{shipmentId}', 'update');
        });

        Route::prefix('questions')->controller(QuestionController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('/{categoryId}', 'show');
            Route::put('/{question}', 'update');
            Route::delete('/{question}', 'destroy');
        });

        Route::prefix('carts')->controller(CartController::class)->group(function () {
            Route::get('/requests', 'showRequestCart');
            Route::get('/{cart}/assign', 'employeeSubmitCart');
            Route::get('/employee', 'showEmployeeCart');
            Route::get('/{cartId}/info', 'showCartInfo');
            Route::get('/{cartId}/shipments', 'showShipmentsCart');
        });

        Route::prefix('shipment-answers')->group(function () {
            Route::post('/', [ShipmentAnswerController::class, 'store']);//->middleware('permission:create.answer');
            Route::get('{shipmentAnswer}', [ShipmentAnswerController::class, 'show']);
            Route::put('{shipmentAnswer}', [ShipmentAnswerController::class, 'update']);
            Route::delete('{shipmentAnswer}', [ShipmentAnswerController::class, 'destroy']);
        });
        Route::prefix('supplier')->group(function () {
            Route::post('/', [SupplierController::class, 'store']);//->middleware('permission:create.supplier');
        });

        Route::prefix('shipment-full')->controller(ShipmentFullController::class)->group(function () {
            Route::get('/{shipmentId}',  'show');//->middleware('permission:show.shipment_full');
            Route::post('/{shipmentId}',  'update');//->middleware('permission:update.shipment_full');
            Route::post('/{shipmentId}',  'delete');//->middleware('permission:delete.shipment_full');
        });






    });





});



