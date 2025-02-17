<?php
$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create',['auth']);
$router->get('/listing/{id}', 'ListingController@show');
$router->get('/listing/edit/{id}', 'ListingController@edit', ['auth']);
$router->post('/listings', 'ListingController@store' , ['auth']);
$router->put('/listing/{id}', 'ListingController@update' , ['auth']);
$router->delete('/listing/{id}', 'ListingController@destroy' , ['auth']);
$router->get('/auth/register', 'UserController@create',['guest']);
$router->get('/auth/login', 'UserController@login' , ['guest']);
$router->post('/auth/register', 'UserController@store', ['guest']);
$router->post('/auth/logout', 'UserController@logout', ['auth']);
$router->post('/auth/login', 'UserController@authenticate' , ['guest']);

