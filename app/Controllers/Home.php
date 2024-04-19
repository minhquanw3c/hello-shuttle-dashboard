<?php

namespace App\Controllers;

use App\Models\ConfigModel;
use App\Models\CarModel;
use App\Models\CustomerModel;
use App\Models\NavigationMenuModel;
use App\Models\ConfigCarPriceModel;

use CodeIgniter\I18n\Time;
use Ramsey\Uuid\Uuid;

class Home extends BaseController
{
    public function getResourcesURLs($resource_type)
    {
        $current_env = strtolower($_SERVER['CI_ENVIRONMENT']);

        $resources = (object) [];

        $resources->form = $current_env === 'production' ? 'https://helloshuttle.project.minhquanle.a2hosted.com/' : 'http://localhost/projects/hello-shuttle/public/';
        $resources->dashboard = $current_env === 'production' ? 'https://helloshuttledashboard.project.minhquanle.a2hosted.com/' : 'http://localhost/projects/hello-shuttle-dashboard/public/';

        return $resources->{$resource_type};
    }

    public function showLoginForm()
    {
        helper('form');

        if (session()->has('logged_in')) {
            return redirect()->to(base_url('bookings'));
        } else {
            $data = [
                'bookingFormUrl' => $this->getResourcesURLs('form')
            ];
            return view('login', $data);
        }
    }

    public function activateAccount()
    {
        
    }

    public function logout()
    {
        if (session()->has('logged_in')) {
            session()->destroy();
            return redirect()->to(base_url('/'));
        } else {
            return view('login');
        }
    }

    public function authoriseUser()
    {
        $form = $this->request->getPost();
        $session = \Config\Services::session();

        $user_data = [
            'email' => $form['email'],
            'password' => $form['password'],
        ];

        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Email must be a valid email address',
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password is required',
                ],
            ],
        ]);

        $input_validity = $validation->run($user_data);

        if (!$input_validity) {
            return redirect()->back()->withInput();
        }

        $user_model = model(UserModel::class);
        $user = $user_model->getUserByEmail($user_data['email']);

        if (count($user) == 0) {
            $session->setFlashdata('_ci_validation_errors', ['account' => 'Email is not existed']);
            return redirect()->back()->withInput();
        }

        $user = $user[0];
        $hash = $user['userPassword'];

        $password_handler = new PasswordManagerController();

        if (!$password_handler->verifyPasswordPairMatch(
            $user_data['password'],
            $hash
        )) {
            $session->setFlashdata('_ci_validation_errors', ['account' => 'Email or password is incorrect']);
            return redirect()->back()->withInput();
        };

        $navigation_menu_model = model(NavigationMenuModel::class);
        $nav_items = $navigation_menu_model->getNavItemsByRole($user['userRole']);

        $logged_in_data = [
            'username' => $user['userEmail'],
            'user_id' => $user['userId'],
            'user_first_name' => $user['userFirstName'],
            'user_last_name' => $user['userLastName'],
            'user_phone' => $user['userPhone'],
            'user_full_name' => $user['userFullName'],
            // 'expiration' => time() + 5,
            'role' => $user['userRole'],
            'allowed_routes' => $user['userAllowedRoutes'],
            'nav_items_data' => $nav_items,
            'default_dashboard_route' => $user['defaultDashboardRoute'],
        ];

        $session->set('logged_in', $logged_in_data);

        return redirect()->to(base_url($user['defaultDashboardRoute']));
    }

    public function showBookings()
    {
        session()->start();

        if (!session()->has('logged_in')) {
            return view('login');
        }

        $user_data = session()->get('logged_in');

        $data = [
            'pageTitle' => 'Bookings',
            'userId' => $user_data['user_id'],
            'bookingFormUrl' => $this->getResourcesURLs('form'),
        ];

        return view($user_data['default_dashboard_route'], $data);
    }

    public function showConfigurations()
    {
        session()->start();

        if (!session()->has('logged_in')) {
            return view('login');
        }

        $data = [
            'pageTitle' => 'Configurations'
        ];

        return view('configurations', $data);
    }

    public function showCoupons()
    {
        session()->start();

        if (!session()->has('logged_in')) {
            return view('login');
        }

        $data = [
            'pageTitle' => 'Coupons'
        ];

        return view('coupons', $data);
    }

    public function showUsers()
    {
        session()->start();

        if (!session()->has('logged_in')) {
            return view('login');
        }

        $user_data = session()->get('logged_in');

        $data = [
            'pageTitle' => 'Users',
            'userRole' => $user_data['role'],
        ];

        return view('users', $data);
    }

    public function getConfigList()
    {
        $config_model = model(ConfigModel::class);

        $config_list = $config_model->getConfigList();

        return $this->response->setJSON($config_list);
    }

    public function getCarsList()
    {
        $car_model = model(CarModel::class);

        $cars_list = $car_model->getCarsList();

        return $this->response->setJSON($cars_list);
    }

    public function getBookingsList()
    {
        $user_id = $this->request->getJsonVar('userId');

        $booking_model = model(BookingModel::class);

        $bookings_list = $booking_model->getBookingsList($user_id);

        return $this->response->setJSON($bookings_list);
    }

    public function getCouponsList()
    {
        $coupon_model = model(CouponModel::class);

        $coupons = $coupon_model->getCoupons();

        return $this->response->setJSON($coupons);
    }

    public function getUsersList()
    {
        $user_model = model(UserModel::class);

        $user_data = session()->get('logged_in');

        $excluded_roles = [];
        array_push($excluded_roles, $user_data['role']);
        $user_data['role'] === 'staff' && array_push($excluded_roles, 'admin');

        $users = $user_model->getUsers($excluded_roles);

        return $this->response->setJSON($users);
    }

    public function createUser($from_api = true, $data = [])
    {
        $user_model = model(UserModel::class);

        if ($from_api) {
            $request_params = $this->request->getJsonVar('form');

            $password_handler = new PasswordManagerController();

            $user_id = Uuid::uuid4()->toString();
            $user_hashed_password = $password_handler->encryptPassword($request_params->userPassword);

            $data = [
                'user_id' => $user_id,
                'user_email' => $request_params->userEmail,
                'user_hashed_password' => $user_hashed_password,
                'user_phone' => $request_params->userPhone,
                'user_first_name' => $request_params->userFirstName,
                'user_last_name' => $request_params->userLastName,
                'user_active' => 1,
                'user_role' => 'staff',
                'user_created_at' => Time::now('UTC'),
                'user_updated_at' => Time::now('UTC'),
            ];
        }

        $create_user_result = $user_model->createUser($data);

        $response = [
            'result' => $create_user_result,
        ];

        return $from_api ? $this->response->setJSON($response) : $response['result'];
    }

    public function editUser($from_api = true, $user_id = null, $data = [])
    {
        $user_model = model(UserModel::class);

        if ($from_api) {
            $request_params = $this->request->getVar('form');

            $user_id = $request_params->userId;

            $data = [
                'user_email' => $request_params->userEmail,
                'user_phone' => $request_params->userPhone,
                'user_first_name' => $request_params->userFirstName,
                'user_last_name' => $request_params->userLastName,
                'user_active' => $request_params->userActive,
                'user_updated_at' => Time::now('UTC'),
            ];
        }

        $edit_user_result = $user_model->updateUserById($user_id, $data);

        $response = [
            'result' => $edit_user_result,
        ];

        return $from_api ? $this->response->setJSON($response) : $response['result'];
    }

    public function createCustomer()
    {
        $customer_model = model(CustomerModel::class);

        $request_params = $this->request->getJsonVar('form');

        $password_handler = new PasswordManagerController();

        $customer_id = Uuid::uuid4()->toString();
        $customer_hashed_password = $password_handler->encryptPassword($request_params->customerPassword);

        $data = [
            'customer_id' => $customer_id,
            'email' => $request_params->customerEmail,
            'phone' => $request_params->customerPhone,
            'first_name' => $request_params->customerFirstName,
            'last_name' => $request_params->customerLastName,
            'has_account' => 1,
            'register_account' => 0,
            'created_at' => Time::now('UTC'),
            'updated_at' => Time::now('UTC'),
        ];

        $user_data = [
            'user_id' => $customer_id,
            'user_email' => $request_params->customerEmail,
            'user_hashed_password' => $customer_hashed_password,
            'user_phone' => $request_params->customerPhone,
            'user_first_name' => $request_params->customerFirstName,
            'user_last_name' => $request_params->customerLastName,
            'user_active' => 1,
            'user_role' => 'customer',
            'user_created_at' => Time::now('UTC'),
            'user_updated_at' => Time::now('UTC'),
        ];

        $create_customer_result = $customer_model->createcustomer($data);
        $create_customer_user_type_result = $this->createUser(false, $user_data);

        $response = [
            'result' => $create_customer_result && $create_customer_user_type_result,
        ];

        return $this->response->setJSON($response);
    }

    public function editCustomer()
    {
        $customer_model = model(CustomerModel::class);

        $request_params = $this->request->getJsonVar('form');

        $customer_data = [
            'email' => $request_params->customerEmail,
            'phone' => $request_params->customerPhone,
            'first_name' => $request_params->customerFirstName,
            'last_name' => $request_params->customerLastName,
            'updated_at' => Time::now('UTC'),
        ];

        $user_data = [
            'user_email' => $request_params->customerEmail,
            'user_phone' => $request_params->customerPhone,
            'user_first_name' => $request_params->customerFirstName,
            'user_last_name' => $request_params->customerLastName,
            'user_active' => $request_params->customerActive,
            'user_updated_at' => Time::now('UTC'),
        ];

        $edit_customer_result = $customer_model->updateCustomerDetails($request_params->customerId, $customer_data);
        $edit_customer_user_type_result = $this->editUser(false, $request_params->customerId, $user_data);

        $response = [
            'result' => $edit_customer_result && $edit_customer_user_type_result,
        ];

        return $this->response->setJSON($response);
    }

    public function createConfig()
    {
        $config_model = model(ConfigModel::class);

        $request_params = $this->request->getVar('form');

        $new_config_data = [
            'config_id' => 'cfg-opt-' . random_string('alnum', 5),
            'config_name' => $request_params->name,
            'config_value' => $request_params->value,
            'config_type_code' => 'cfg-01',
            'config_group_code' => $request_params->type == 'extras' ? 'cfg-gr-opt' : 'cfg-gr-prt',
            'config_active' => 1,
            'config_editable' => 1,
            'config_maximum_quantity' => $request_params->maximumQuantity,
            'config_created_at' => Time::now('UTC'),
            'config_updated_at' => Time::now('UTC'),
        ];

        $create_config_result = $config_model->createConfig($new_config_data);

        $response = [
            'result' => $create_config_result,
        ];

        return $this->response->setJSON($response);
    }

    public function editConfig()
    {
        $config_model = model(ConfigModel::class);

        $request_params = $this->request->getVar('form');

        $edit_config_result = $config_model->editConfig($request_params);

        $response = [
            'result' => $edit_config_result,
        ];

        return $this->response->setJSON($response);
    }

    public function createCar()
    {
        helper('text');

        $car_model = model(CarModel::class);
        $car_price_config_model = model(ConfigCarPriceModel::class);

        $request_params = $this->request->getVar('form');
        $request_params->carId = random_string('alpha', 8);

        $create_car_result = $car_model->createCar($request_params);
        $create_car_price_config_result = $car_price_config_model->createCarPriceConfig($request_params);

        $response = [
            'result' => $create_car_result && $create_car_price_config_result,
        ];

        return $this->response->setJSON($response);
    }

    public function editCar()
    {
        $car_model = model(CarModel::class);
        $car_price_config_model = model(ConfigCarPriceModel::class);

        $request_params = $this->request->getVar('form');

        $edit_car_result = $car_model->editCar($request_params);
        $edit_car_price_config_result = $car_price_config_model->editCarPriceConfig($request_params);

        $response = [
            'result' => $edit_car_result && $edit_car_price_config_result,
        ];

        return $this->response->setJSON($response);
    }

    public function createCoupon()
    {
        $coupon_model = model(CouponModel::class);

        $request_params = $this->request->getVar('form');

        $coupon_id = Uuid::uuid4()->toString();

        $data = [
            'coupon_id' => $coupon_id,
            'coupon_code' => $request_params->code,
            'discount_amount' => $request_params->discountAmount,
            'is_percentage' => $request_params->isPercentage,
            'start_date' => $request_params->startDate,
            'end_date' => $request_params->endDate,
            'created_at' => Time::now('UTC'),
            'updated_at' => Time::now('UTC'),
        ];

        $create_coupon_result = $coupon_model->createCoupon($data);

        $response = [
            'result' => $create_coupon_result,
            'message' => $create_coupon_result ? 'Coupon created' : 'Error occurred'
        ];

        return $this->response->setJSON($response);
    }

    public function editCoupon()
    {
        $coupon_model = model(CouponModel::class);

        $request_params = $this->request->getVar('form');

        $coupon_id = $request_params->couponId;
        $data = [
            'coupon_code' => $request_params->code,
            'discount_amount' => $request_params->discountAmount,
            'is_percentage' => $request_params->isPercentage,
            'start_date' => $request_params->startDate,
            'end_date' => $request_params->endDate,
            'updated_at' => Time::now('UTC'),
        ];

        $edit_coupon_result = $coupon_model->updateCouponById($coupon_id, $data);

        $response = [
            'result' => $edit_coupon_result,
        ];

        return $this->response->setJSON($response);
    }

    public function editBooking()
    {
        $request_params = (object) $this->request->getVar('form');

        $booking_model = model(BookingModel::class);
        $customer_model = model(CustomerModel::class);

        $booking_data = $booking_model->getColumnValueByKeys($request_params->bookingId, 'booking_data');
        $customer_id = $booking_model->getColumnValueByKeys($request_params->bookingId, 'booked_by_customer');

        $booking_data = json_decode($booking_data);

        $booking_data->review->customer->firstName = $request_params->customerFirstName;
        $booking_data->review->customer->lastName = $request_params->customerLastName;
        $booking_data->review->customer->contact->mobileNumber = $request_params->customerPhone;

        $update_booking_result = $booking_model->updateBookingById(
            $request_params->bookingId,
            [
                'booking_data' => json_encode($booking_data)
            ]
        );

        $update_customer_result = $customer_model->updateCustomerDetails(
            $customer_id,
            [
                'first_name' => $request_params->customerFirstName,
                'last_name' => $request_params->customerLastName,
                'phone' => $request_params->customerPhone,
            ]
        );

        $response = [
            'result' => $update_booking_result && $update_customer_result
        ];

        return $this->response->setJSON($response);
    }

    public function completeBooking()
    {
        $response = [
            'result' => false,
            'message' => 'There are errors occurred',
        ];

        $booking_id = $this->request->getJSONVar('booking_id');

        $booking_exist = $this->checkBookingAvailability($booking_id);

        if (!$booking_exist) {
            $this->response->setJSON($response);
        }

        $booking_model = model(BookingModel::class);
        $booking_schedule_model = model(BookingScheduleModel::class);

        $update_data = [
            'booking_status' => 'bk-sts-cpt'
        ];

        $update_booking_status = $booking_model->updateBookingById($booking_id, $update_data);
        $remove_booking_schedule = $booking_schedule_model->removeBookingScheduleById($booking_id);

        $response['result'] = $update_booking_status && $remove_booking_schedule;
        $response['message'] = $update_booking_status && $remove_booking_schedule ? 'Booking updated successfully' : 'There are errors occurred';

        return $this->response->setJSON($response);
    }

    private function checkBookingAvailability($booking_id)
    {
        $booking_model = model(BookingModel::class);

        $result = $booking_model->getBookingById($booking_id) != null ? true : false;

        return $result;
    }

    public function scheduleBookingCompleteDate()
    {
        $response = [
            'result' => false,
            'message' => 'There are errors occurred',
        ];

        $request_params = $this->request->getJsonVar('form');
        $booking_id = $request_params->bookingId;

        $schedule_model = model(BookingScheduleModel::class);

        $estimated_complete_data = [
            'oneWayTrip' => [
                'estimated_complete_date' => $request_params->oneWayTrip->scheduleCompleteDate,
                'estimated_complete_time' => $request_params->oneWayTrip->scheduleCompleteTime,
            ],
            'roundTrip' => [
                'estimated_complete_date' => $request_params->roundTrip->scheduleCompleteDate,
                'estimated_complete_time' => $request_params->roundTrip->scheduleCompleteTime,
            ],
        ];

        $update_query = $schedule_model
                            ->set($estimated_complete_data['oneWayTrip'])
                            ->where([
                                'booking_id' => $booking_id,
                                'car_id' => $request_params->oneWayTrip->carId
                            ])->update();

        if ($request_params->tripType === 'round-trip') {
            $update_query = $schedule_model
                            ->set($estimated_complete_data['roundTrip'])
                            ->where([
                                'booking_id' => $booking_id,
                                'car_id' => $request_params->roundTrip->carId
                            ])->update();
        }

        $response['result'] = $update_query;
        $response['message'] = $update_query ? 'Scheduling book successfully' : 'There are errors occurred';

        return $this->response->setJSON($response);
    }

    public function showAccountSettings()
    {
        if (!session()->has('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'pageTitle' => 'Account settings'
        ];

        return view('account_settings', $data);
    }
}
