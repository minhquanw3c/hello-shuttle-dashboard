<?php

namespace App\Controllers;
use App\Models\ConfigModel;
use App\Models\CarModel;

use CodeIgniter\I18n\Time;
use Ramsey\Uuid\Uuid;

class Home extends BaseController
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showBookings()
    {
        $data = [
            'pageTitle' => 'Bookings'
        ];

        return view('bookings', $data);
    }

    public function showConfigurations()
    {
        $data = [
            'pageTitle' => 'Configurations'
        ];

        return view('configurations', $data);
    }

    public function showCoupons()
    {
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
}
