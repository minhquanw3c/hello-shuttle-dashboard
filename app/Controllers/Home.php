<?php

namespace App\Controllers;

use App\Models\ConfigModel;
use App\Models\CarModel;
use App\Models\CustomerModel;
use CodeIgniter\I18n\Time;
use Ramsey\Uuid\Uuid;

class Home extends BaseController
{
    public function showLoginForm()
    {
        helper('form');

        if (session()->has('logged_in')) {
            return redirect()->to(base_url('bookings'));
        } else {
            return view('login');
        }
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

        if (!password_verify($user_data['password'], $hash)) {
            $session->setFlashdata('_ci_validation_errors', ['account' => 'Email or password is incorrect']);
            return redirect()->back()->withInput();
        };

        $logged_in_data = [
            'username' => $user_data['email'],
            // 'expiration' => time() + 5,
            'role' => $user['userRole'],
            'allowed_routes' => $user['userAllowedRoutes'],
        ];

        $session->set('logged_in', $logged_in_data);

        return redirect()->to(base_url('bookings'));
    }

    public function showBookings()
    {
        session()->start();
        $data = [
            'pageTitle' => 'Bookings'
        ];

        return view('bookings', $data);
    }

    public function showConfigurations()
    {
        session()->start();
        $data = [
            'pageTitle' => 'Configurations'
        ];

        return view('configurations', $data);
    }

    public function showCoupons()
    {
        session()->start();
        $data = [
            'pageTitle' => 'Coupons'
        ];

        return view('coupons', $data);
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
        $booking_model = model(BookingModel::class);

        $bookings_list = $booking_model->getBookingsList();

        return $this->response->setJSON($bookings_list);
    }

    public function getCouponsList()
    {
        $coupon_model = model(CouponModel::class);

        $coupons = $coupon_model->getCoupons();

        return $this->response->setJSON($coupons);
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

    public function editCar()
    {
        $car_model = model(CarModel::class);

        $request_params = $this->request->getVar('form');

        $edit_car_result = $car_model->editCar($request_params);

        $response = [
            'result' => $edit_car_result,
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

    public function clearBookings()
    {
        $booking_model = model(BookingModel::class);
        $booking_schedule_model = model(BookingScheduleModel::class);

        $clear_bookings_result = $booking_model->clearBookings();
        $clear_schedules_result = $booking_schedule_model->clearSchedules();

        $response = [
            'result' => $clear_bookings_result && $clear_schedules_result
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
}
