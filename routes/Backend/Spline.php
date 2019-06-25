<?php

Route::group([
    'namespace' => 'Spline',
], function () {

    /**
     * Spline Management Routes
     */
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-users',
    ], function () {


    });
});