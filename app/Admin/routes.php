<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $exclude_edit_update_destroy = [ 'edit', 'update', 'destroy' ];
    $exclude_destroy = ['destroy'];

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->resource('projects', ProjectController::class);
    $router->resource('phases', PhaseController::class);
    $router->resource('blocks', BlockController::class);
    $router->resource('property-types', PropertyTypeController::class);
    
    $router->resource('land-purchases', LandPurchaseController::class);
    $router->resource('property-files', PropertyFileController::class);
    $router->resource('dealer-bookings', DealerBookingController::class)->except($exclude_edit_update_destroy);
    $router->resource('dealer-booking-returns', DealerBookingReturnController::class)->except($exclude_edit_update_destroy);

    $router->resource('people', PersonController::class);
    
    $router->resource('bookings', BookingController::class)->except($exclude_destroy);
    $router->resource('booking-cancellations', BookingCancellationController::class)->except($exclude_destroy);
    $router->resource('payment-plans', PaymentPlanController::class);
    $router->resource('allotments', AllotmentController::class)->except($exclude_destroy);
    $router->resource('allotment-cancellations', AllotmentCancellationController::class)->except($exclude_destroy);
    $router->resource('possessions', PossessionController::class)->except($exclude_destroy);
    $router->resource('possession-cancellations', PossessionCancellationController::class)->except($exclude_destroy);
    $router->resource('transfers', TransferController::class)->except($exclude_destroy);

    $router->resource('account-heads', AccountHeadController::class);

    $router->resource('journal-vouchers', JournalVoucherController::class);

    $router->get('{entity}/{entity_id}/print', 'PrintController@print');

    $router->get('receivables-payables-report', 'ReportController@receivablesPayablesReport');
    $router->get('receivables-payables-detail-report', 'ReportController@receivablesPayablesDetailReport');
    $router->get('trial-balance-report', 'ReportController@trialBalanceReport');
    $router->get('ledger-report', 'ReportController@showLedger');

    $router->get('dealers-files-report', 'ReportController@dealersFilesReport');

    $router->get('instalments-due-report', 'ReportController@instalmentsDueReport');
    $router->get('property-files-collections', 'ReportController@propertyFilesCollections');
    $router->get('payment-plan-letter', 'ReportController@paymentPlanLetter');
    $router->get('property-inventory', 'ReportController@propertyInventory');

    $router->resource('property-marlas', PropertyMarlaController::class);
    
    $router->resource('instalment-receipts', InstalmentReceiptController::class);
    $router->resource('payment-plan-types', PaymentPlanTypeController::class);
});
