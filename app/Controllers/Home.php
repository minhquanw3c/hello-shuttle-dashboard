<?php

namespace App\Controllers;
use App\Models\ConfigModel;
use App\Models\CarModel;

class Home extends BaseController
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showBookings()
    {
        return view('bookings');
    }

    public function showConfigurations()
    {
        return view('configurations');
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
}
