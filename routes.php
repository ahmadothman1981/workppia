<?php
$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create');
$router->get('/listing/{id}', 'ListingController@show');
$router->get('/listing/edit/{id}', 'ListingController@edit');
$router->post('/listings', 'ListingController@store');
$router->put('/listing/{id}', 'ListingController@update');
$router->delete('/listing/{id}', 'ListingController@destroy');

