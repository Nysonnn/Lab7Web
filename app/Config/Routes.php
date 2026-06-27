<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

$routes->get('/artikel', 'Artikel::index');
$routes->get('/kategori/(:segment)', 'Artikel::kategori/$1');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

$routes->match(['GET', 'POST'], 'user/login', 'User::login');
$routes->get('user/logout', 'User::logout');

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->match(['GET', 'POST'], 'artikel/add', 'Artikel::add');
    $routes->match(['GET', 'POST'], 'artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:num)', 'Artikel::delete/$1');
});

$routes->group('ajax', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'AjaxController::index');
    $routes->get('getData', 'AjaxController::getData');
    $routes->post('create', 'AjaxController::create');
    $routes->put('update/(:num)', 'AjaxController::update/$1');
    $routes->delete('delete/(:num)', 'AjaxController::delete/$1');
});
