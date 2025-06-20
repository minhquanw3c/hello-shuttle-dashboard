<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::showLoginForm');
$routes->post('login', 'Home::authoriseUser');
$routes->get('logout', 'Home::logout');
$routes->get('bookings', 'Home::showBookings');
$routes->get('configurations', 'Home::showConfigurations');
$routes->get('coupons', 'Home::showCoupons');
$routes->get('users', 'Home::showUsers');

$routes->get('customer/bookings', 'Home::showBookings');
$routes->post('api/bookings/customer/list', 'Home::getBookingsList');

$routes->get('api/configurations/list', 'Home::getConfigList');
$routes->get('api/cars/list', 'Home::getCarsList');
$routes->post('api/bookings/list', 'Home::getBookingsList');
$routes->get('api/coupons/list', 'Home::getCouponsList');
$routes->post('api/coupons/reset', 'Home::resetCoupons');

$routes->post('api/configurations/edit', 'Home::editConfig');
$routes->post('api/configurations/create', 'Home::createConfig');
$routes->post('api/configurations/reset', 'Home::resetConfigurations');

$routes->post('api/cars/edit', 'Home::editCar');
$routes->post('api/cars/create', 'Home::createCar');
$routes->post('api/cars/reset', 'Home::resetCarConfigurations');

$routes->post('api/coupons/create', 'Home::createCoupon');
$routes->post('api/coupons/edit', 'Home::editCoupon');
$routes->post('api/bookings/clear', 'Home::clearBookings');
$routes->post('api/bookings/edit', 'Home::editBooking');

$routes->post('api/users/list', 'Home::getUsersList');
$routes->post('api/users/edit', 'Home::editUser');
$routes->post('api/users/create', 'Home::createUser');
$routes->post('api/users/reset', 'Home::resetUsers');

$routes->post('api/customers/edit', 'Home::editCustomer');
$routes->post('api/customers/create', 'Home::createCustomer');

$routes->post('api/bookings/complete', 'Home::completeBooking');
$routes->post('api/bookings/schedule', 'Home::scheduleBookingCompleteDate');

$routes->get('account/settings', 'Home::showAccountSettings');
$routes->post('account/settings/update', 'PasswordManagerController::changePasswordGateway');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
