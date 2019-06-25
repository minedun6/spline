<?php

Route::group([
    'namespace' => 'Spline',
], function () {

    /**
     * Spline Management Routes
     */
    Route::group([
        'middleware' => 'auth'
    ], function () {

        Route::group(['namespace'=>'Commande'], function ()
        {
            // Planifications
            Route::get('/planifications', [
                'as' => 'commandes.planifications',
                'uses' => 'PlanificationController@planificationsIndex'
            ]);

            Route::get('/planifications/all', [
                'as' => 'commandes.planifications.all',
                'uses' => 'PlanificationController@getAllPlanifications'
            ]);

            Route::get('/planifications/annuler/{id}', [
                'as' => 'commandes.planifications.annuler',
                'uses' => 'PlanificationController@annulerPlanification'
            ]);

            Route::get('/planifications/create', [
                'as' => 'commandes.planifications.create',
                'uses' => 'PlanificationController@planifierCreate'
            ]);

            Route::post('/planifications/store', [
                'as' => 'commandes.planifications.store',
                'uses' => 'PlanificationController@planifierStore'
            ]);


            Route::get('/commandes/getAllCommandesForPose', [
                'as' => 'commandes.getAllCommandesForPose',
                'uses' => 'PlanificationController@getAllCommandesForPose'
            ]);

            Route::get('/planifications/getPlanificationForFullCallendar', [
                'as' => 'commandes.planifications.getPlanificationForFullCallendar',
                'uses' => 'PlanificationController@getPlanificationForFullCallendar'
            ]);

            // Poses
            Route::get('/mes-poses', [
                'as' => 'commandes.poses',
                'uses' => 'PlanificationController@posesIndex'
            ]);
            Route::post('/mes-poses/{id}/validate', [
                'as' => 'commandes.poses.validate',
                'uses' => 'PlanificationController@posesValidate'
            ]);

            Route::post('/mes-poses/{id}/reporter', [
                'as' => 'commandes.poses.reporter',
                'uses' => 'PlanificationController@posesReporter'
            ]);

            Route::post('/mes-poses/{id}/refuser', [
                'as' => 'commandes.poses.refuser',
                'uses' => 'PlanificationController@posesRefuser'
            ]);

            // Commandes
            Route::get('/commandes/getAllCommandes', [
                'as' => 'commandes.getAllCommandes',
                'uses' => 'CommandeController@getAllCommandes'
            ]);

            Route::get('/commandes/{id}/validate', [
                'as' => 'commandes.validateCommande',
                'uses' => 'CommandeController@validateCommande'
            ]);   

            Route::get('/commandes/{id}/finish', [
                'as' => 'commandes.finishCommande',
                'uses' => 'CommandeController@finishCommande'
            ]);  

            Route::get('/commandes/{id}/print', [
                'as' => 'commandes.printCommande',
                'uses' => 'CommandeController@printCommande'
            ]);   

            Route::get('/commandes/{id}/cancel', [
                'as' => 'commandes.cancelCommande',
                'uses' => 'CommandeController@cancelCommande'
            ]);   

            Route::get('/commandes/{id}/status', [
                'as' => 'commandes.changeStatus',
                'uses' => 'CommandeController@changeStatus'
            ]);   

            Route::get('/commandes/{id}/restore', [
                'as' => 'commandes.restore',
                'uses' => 'CommandeController@restore'
            ]);   

            // Upload
            Route::post('/commandes/{id}/upload', [
                'as' => 'commandes.upload',
                'uses' => 'CommandeController@upload'
            ]);

            Route::resource('commandes', 'CommandeController');

            Route::group([
                'middleware' => 'access.routeNeedsPermission:create-orders'
            ], function ()
            {
                Route::get('/commandes/create', [
                'as' => 'commandes.create',
                'uses' => 'CommandeController@create']);
            
            });
        });

        Route::get('/products/getAllProducts', [
            'as' => 'products.getAllProducts',
            'uses' => 'ProductController@getAllProducts'
        ]);
        
        Route::get('/products/getByClient', [
            'as' => 'products.getByClient',
            'uses' => 'ProductController@getByClient'
        ]);
        
        Route::resource('products', 'ProductController');


        Route::group(['namespace'=>'Pharmacie'], function ()
        {
            Route::get('/pharmacies/getPharmacieVitrines/{id}', [
                'as' => 'pharmacies.getPharmacieVitrines',
                'uses' => 'PharmacieController@getPharmacieVitrines'
            ]);

            Route::get('/pharmacie/getAllPharmacies', [
                'as' => 'pharmacie.getAllPharmacies',
                'uses' => 'PharmacieController@getAllPharmacies'
            ]);

            Route::get('/pharmacie/createPartial', [
                'as' => 'pharmacie.createPartial',
                'uses' => 'PharmacieController@createPartial'
            ]);

            Route::get('/pharmacie/getDelegues', [
                'as' => 'pharmacie.getDelegues',
                'uses' => 'PharmacieController@getDelegues'
            ]);

            Route::post('/pharmacie/addDelegueToPharmacie', [
                'as' => 'pharmacie.addDelegueToPharmacie',
                'uses' => 'PharmacieController@addDelegueToPharmacie'
            ]);

            Route::post('/pharmacie/addDelegue', [
                'as' => 'pharmacie.addDelegue',
                'uses' => 'PharmacieController@addDelegue'
            ]);

            Route::resource('pharmacies', 'PharmacieController');
        });


        Route::group(['namespace'=>'Collaborateur'], function ()
        {
           Route::get('/collaborateurs/get', [
                'as' => 'collaborateurs.get',
                'uses' => 'CollaborateurController@getCollaborateurs'
            ]);        
            Route::resource('collaborateurs', 'CollaborateurController');
        });

        Route::group(['namespace'=>'Delegue'], function ()
        {
            Route::get('/delegues/getAllDelegues', [
                'as' => 'delegues.getAllDelegues',
                'uses' => 'DelegueController@getAllDelegues'
            ]);
            
            Route::get('/delegues/assign', [
                'as' => 'delegues.assign',
                'uses' => 'DelegueController@assign'
            ]);
            
            Route::post('/delegues/storeAssign', [
                'as' => 'delegues.assign.store',
                'uses' => 'DelegueController@storeAssign'
            ]);

            Route::get('/delegues/remove/{id}', [
                'as' => 'delegues.assign.remove',
                'uses' => 'DelegueController@remove'
            ]);
            
            Route::resource('delegues', 'DelegueController');
        });
    });
});