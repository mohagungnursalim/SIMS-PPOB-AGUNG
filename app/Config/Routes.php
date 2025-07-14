<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman utama 
$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Auth 
$routes->get('/register', 'Auth::showRegister', ['filter' => 'alreadyLoggedIn']);
$routes->post('register', 'Auth::register', ['filter' => 'alreadyLoggedIn']);
$routes->get('/login', 'Auth::showLogin', ['filter' => 'alreadyLoggedIn']);
$routes->post('login', 'Auth::login', ['filter' => 'alreadyLoggedIn']);

// Topup
$routes->get('/topup', 'Topup::showForm', ['filter' => 'auth']);
$routes->post('topup', 'Topup::process', ['filter' => 'auth']);

// Payment
$routes->get('/payment/(:segment)', 'Payment::index/$1', ['filter' => 'auth']); // get payment:service_code
$routes->post('transaction', 'Payment::transaction', ['filter' => 'auth']); 

// Transaction History
$routes->get('transactions', 'Transaction::index', ['filter' => 'auth']); 
$routes->get('transactions/load', 'Transaction::loadMore', ['filter' => 'auth']); // API JSON

// Profile
$routes->get('profile', 'Profile::index', ['filter' => 'auth']);
$routes->post('profile/update', 'Profile::update', ['filter' => 'auth']);
$routes->post('profile/image', 'Profile::updateImage', ['filter' => 'auth']);




// Logout
$routes->get('logout', 'Profile::logout');
