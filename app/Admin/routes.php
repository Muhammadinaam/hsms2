<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->resource('projects', ProjectController::class);
    $router->resource('phases', PhaseController::class);
    $router->resource('blocks', BlockController::class);
    $router->resource('property-types', PropertyTypeController::class);
    
    $router->resource('property-files', PropertyFileController::class);
    $router->resource('dealer-bookings', DealerBookingController::class);
    $router->resource('dealer-booking-returns', DealerBookingReturnController::class);

    $router->resource('properties', PropertyController::class);
    $router->resource('people', PersonController::class);
    $router->resource('bookings', BookingController::class);
    $router->resource('booking-cancellations', BookingCancellationController::class);
    $router->resource('allotments', AllotmentController::class);
    $router->resource('allotment-cancellations', AllotmentCancellationController::class);
    $router->resource('possessions', PossessionController::class);
    $router->resource('possession-cancellations', PossessionCancellationController::class);
    $router->resource('transfers', TransferController::class);

    $router->resource('account-heads', AccountHeadController::class);

});
