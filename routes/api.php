
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Company\OriginalShippingCompanyController;
use App\Http\Controllers\Complaint\AdminGeneralComplaintController;
use App\Http\Controllers\Complaint\CustomerGeneralComplaintController;
use App\Http\Controllers\Contract\ContractController;
use App\Http\Controllers\Contract\ContractDownloadController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerRequestController;
use App\Http\Controllers\Invoice\OrderInvoiceController;
use App\Http\Controllers\Invoice\ShipmentInvoiceController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\RequestOrderController;
use App\Http\Controllers\Order\SendOrderController;
use App\Http\Controllers\OrderTrackingLogController;
use App\Http\Controllers\Payment\MyFatoorahPaymentController;
use App\Http\Controllers\Payment\PaymentInfoController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Rate\RateOrderController;
use App\Http\Controllers\Report\AdminReportController;
use App\Http\Controllers\Report\ShippingManagerReportController;
use App\Http\Controllers\Route\ShipmentRouteController;
use App\Http\Controllers\Shipment\ShipmentAnswerController;
use App\Http\Controllers\Shipment\ShipmentController;
use App\Http\Controllers\Shipment\ShipmentFullController;
use App\Http\Controllers\Shipment\ShipmentStatusController;
use App\Http\Controllers\User\ManageCustomerController;
use App\Http\Controllers\User\UserManagementController;
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

        Route::prefix('customer/request')->controller(CustomerRequestController::class)->group(function () {
            Route::get('/show', 'show');
            Route::get('/{user}/details', 'showRequestDetails');
            Route::get('/{user}/accept', 'accept');
            Route::get('/{user}/reject', 'reject');
        });

        Route::prefix('order')->controller(OrderController::class)->group(function () {
            Route::get('/employee', 'showEmployeeOrders');
            Route::get('/shipping-manager', 'showShippingManagerOrders');
            Route::get('/accountant', 'showAccountantOrders');
            Route::get('/{orderId}', 'showOrder');
            Route::get('/shipments/{orderId}', 'showShipmentsOrder');
            Route::post('/confirm', 'changeStatusToConfirm');
            Route::post('/change_status/{order}', 'changeOrderStatus');

        });


Route::prefix('show/order')->controller(OrderController::class)->group(function () {
    Route::get('/confirmed-customer', 'showConfirmedOrders');//->middleware('can:show.confirmed.order');
    Route::get('/unconfirmed-customer', 'showUnconfirmedOrders');//->middleware('can:show.unconfirmed.order');
    Route::get('/delivered', 'showDeliveredOrders');//->middleware('can:show.unconfirmed.order');

});


        Route::prefix('shipment')->controller(ShipmentController::class)->group(function () {
            Route::post('/', 'store')->middleware('can:create.shipment');
            Route::get('/{shipmentId}', 'show');
            Route::post('/update/{shipmentId}', 'update');
        });


        Route::prefix('order/request')->controller(RequestOrderController::class)->group(function () {
            Route::get('/employee', 'showEmployeeOrderRequest');
            Route::get('/employee/accept/{order}', 'acceptEmployeeOrder');
        });

        Route::prefix('order/send')->controller(SendOrderController::class)->group(function () {
            Route::get('/shipping_manager/{order}', 'sendOrderToShippingManager');
            Route::get('/accountant/{order}', 'sendOrderToAccountant');
        });

        Route::prefix('categories')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/{id}/with-questions', 'showWithQuestions');
            Route::post('/', 'store');
            Route::post('/update/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });


        Route::prefix('shipment')->controller(ShipmentStatusController::class)->group(function () {
            Route::get('/complete-info/{shipment}', 'changeToComplete');
            Route::get('/confirm/{shipment}', 'ChangeToConfirm');
            Route::post('/change_status/{shipment}', 'ChangeShipmentStatus');

        });

        Route::prefix('questions')->controller(QuestionController::class)->group(function () {
            Route::post('/store', 'store');
            Route::get('/{categoryId}', 'show');
            Route::get('/{questionId}/question', 'showQuestion');
            Route::put('/{question}', 'update');
            Route::delete('/{question}', 'destroy');
        });

        Route::prefix('categories')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/{id}/with-questions', 'showWithQuestions');
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
        Route::prefix('carts')->controller(CartController::class)->group(function () {
            Route::get('/cartshipments', 'showShipmentsCart');//->middleware('can:show.shipments.cart');
            Route::get('/send', 'send');//->middleware('can:send.shipments.cart');

        });

        Route::prefix('shipment-answers')->group(function () {
            Route::post('/', [ShipmentAnswerController::class, 'store'])->middleware('can:create.answer');
            Route::get('{shipmentAnswer}', [ShipmentAnswerController::class, 'show']);
            Route::put('{shipmentAnswer}', [ShipmentAnswerController::class, 'update']);
            Route::delete('{shipmentAnswer}', [ShipmentAnswerController::class, 'destroy']);
        });


        Route::prefix('shipment-full')->controller(ShipmentFullController::class)->group(function () {
            Route::get('/{shipmentId}', 'show');//->middleware('can:show.shipment.full');
            Route::post('/{shipmentId}', 'update');//->middleware('can:update.shipment.full');
            Route::delete('/{shipmentId}', 'delete');//->middleware('can:delete.shipment.full');
        });
        Route::prefix('original-shipping-companies')->controller(OriginalShippingCompanyController::class)->group(function () {


            Route::get('/all', 'index');
            Route::post('/', 'store');//->middleware('can:create.company');
            Route::get('/{originalShippingCompany}', 'show');//->middleware('can:show.company');
            Route::post('/update/{originalShippingCompany}', 'update');//->middleware('can:update.company');
            Route::delete('/{originalShippingCompany}', 'destroy');//->middleware('can:delete.company');
        });
        Route::prefix('companies')->controller(OriginalShippingCompanyController::class)->group(function () {

            Route::post('/{shipment}', 'addAndAssignCompany');//->middleware('can:add.and.assign.company');
            Route::post('/{shipment}/{originalShippingCompany}', 'selectCompany');//->middleware('can:select.company');
            Route::get('/{shipment}',  'showShipmentWithCompany');
        });

        Route::prefix('invoice')->controller(ShipmentInvoiceController::class)->group(function () {
            Route::post('/create/{shipment}', 'create');
            Route::get('/show/{shipment}', 'show');
            Route::post('/update/{invoice}', 'update');
            Route::delete('/delete/{invoice}', 'delete');
            Route::get('/{invoice}/download', 'download');
        });

        Route::prefix('invoice/order')->controller(OrderInvoiceController::class)->group(function () {
            Route::get('/create/{order}', 'create');
            Route::get('/show/{order}', 'show');
            Route::delete('/delete/{invoice}', 'delete');
            Route::get('/{invoice}/download', 'download');
        });


        Route::prefix('orders/chat')->middleware('auth:sanctum')->group(function () {
            Route::post('{order}/send', [MessageController::class, 'sendMessage']);
            Route::get('{order}/messages', [MessageController::class, 'getMessages']);
        });

        Route::prefix('payments')->middleware('auth:sanctum')->group(function () {

            Route::post('info/{order}', [PaymentInfoController::class, 'show']);

            /*   Route::controller(StripePaymentController::class)->group(function () {
                   Route::post('pay/{order}', 'pay');
                   Route::post('verify','verify');
               });*/

            Route::controller(MyFatoorahPaymentController::class)->group(function () {
                Route::post('pay', 'pay');
                Route::post('verify', 'verify');
                Route::get('callback', 'callback')->name('payments.myfatoorah.callback');
            });

        });

        Route::prefix('order-routes')->controller(ShipmentRouteController::class)->group(function () {
            Route::get('/{shipment}', 'showByShipment');
            Route::post('/', 'store');
            Route::post('/{ShipmentRoute}', 'update');
            Route::delete('{ShipmentRoute}', 'destroy');
            Route::get('/show/{ShipmentRoute}', 'show');
        });

        Route::prefix('order-logs')->controller(OrderTrackingLogController::class)->group(function () {
            Route::post('/', 'store');
            Route::post('/{shipmentTracking}', 'update');
            Route::delete('{shipmentTracking}', 'destroy');
        });

        Route::prefix('admin/users')->controller(UserManagementController::class)->group(function () {
            // Employees
            Route::post('employees', 'addEmployee');
            Route::get('show/employees', 'employees');
            Route::post('update/employees/{id}', 'updateEmployee');

            // Accountants
            Route::post('accountants', 'addAccountant');
            Route::get('show/accountants', 'accountants');
            Route::post('update/accountants/{id}', 'updateAccountant');

            // Shipment Managers
            Route::post('shipment-managers', 'addShipmentManager');
            Route::get('show/shipment-managers', 'shipmentManagers');
            Route::post('update/shipment-managers/{id}', 'updateShipmentManager');

            // Common
            Route::get('info/{id}', 'show');
            Route::delete('delete/{id}', 'delete');
        });


Route::prefix('admin/customers')->middleware('role:admin')->controller(ManageCustomerController::class)->group(function () {
    Route::get('/view', 'index');
    Route::get('/show/{customer}', 'show');
    Route::delete('delete/{customer}', 'destroy');
});

        Route::prefix('customer/profile')->controller(CustomerProfileController::class)->group(function () {
            Route::get('/show', 'show');
            Route::post('/update', 'update');
            Route::post('/upload/image', 'uploadImage');
            Route::delete('/delete/image', 'deleteImage');
        });

        Route::prefix('orders')->middleware(['auth:sanctum', 'role:customer'])->controller(RateOrderController::class)->group(function () {
            Route::post('{order}/rate', 'store');
            Route::get('{order}/show/rate', 'show');
        });

        Route::middleware(['auth:sanctum', 'role:customer'])
            ->prefix('customer/complaints')->controller(CustomerGeneralComplaintController::class)->group(function () {
                Route::get('/show', 'index');
                Route::post('/store', 'store');
                Route::get('{id}', 'show');
            });

        Route::middleware(['auth:sanctum', 'role:admin'])
            ->prefix('admin/complaints')->controller(AdminGeneralComplaintController::class)->group(function () {
                Route::get('/show', 'index');
                Route::get('{complaint}', 'show');
                Route::post('{complaint}/reply', 'reply');
                Route::post('{complaint}/resolve', 'resolve');
            });

        Route::prefix('reports')->group(function () {
            Route::get('/admin', [AdminReportController::class, 'index']);
            Route::get('/shipping-manager', [ShippingManagerReportController::class, 'index']);
        });

        Route::prefix('contracts')->group(function () {
            // قائمة عقود الشحنة
            Route::get('shipment/{shipment}', [ContractController::class, 'index']);

            // توليد/تحميل ملفات “من النظام” (PDF) بالاعتماد على الشحنة
            Route::get('service/{shipment}/download', [ContractDownloadController::class, 'downloadService']);
            Route::get('goods/{shipment}/download',   [ContractDownloadController::class, 'downloadGoods']);

            // تحميل بوليصة الشحن (المرفوعة من الموظف) بحسب الشحنة
            Route::get('bol/{shipment}/download',     [ContractDownloadController::class, 'downloadBOLByShipment']);

            // تحميل نسخة موقعة لعقد الخدمة بحسب الشحنة (إن وُجدت)
            Route::get('service/{shipment}/download-signed', [ContractDownloadController::class, 'downloadSignedByShipment']);

            // رفع بوليصة الشحن (موظف)
            Route::post('shipment/{shipment}/bol',             [ContractController::class, 'uploadBillOfLading']);

            // رفع عقد الخدمة الموقّع (عميل)
            Route::post('shipment/{shipment}/service/signed',  [ContractController::class, 'uploadSignedService']);
        });
    });
});
