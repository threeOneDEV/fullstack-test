<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'CommentController::index');
$routes->get('/get-comments', 'CommentController::getComments');
$routes->post('/store', 'CommentController::store');
$routes->delete('/delete/(:num)', 'CommentController::delete/$1');
