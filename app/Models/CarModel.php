<?php

namespace App\Models;

use CodeIgniter\Model;

class CarModel extends Model
{
	protected $table = 'config_cars';
	protected $primaryKey = 'car_id';

	protected $allowedFields = [
        'car_name',
        'car_seats_capacity',
        'car_quantity',
        'car_editable',
        'car_active',
        'car_start_price',
        'car_created_at',
        'car_updated_at',
	];

    public function getCarsList()
    {
        $get_list_query = $this->select([
            'config_cars.car_id AS carId',
            'config_cars.car_name AS carName',
            'config_cars.car_seats_capacity AS carSeatsCapacity',
            'config_cars.car_quantity AS carQuantity',
            'config_cars.car_active AS carActive',
            'config_cars.car_editable AS carEditable',
            'config_cars.car_image AS carImage',
            // ----
            'config_cars_price.open_door_price AS openDoorPrice',
            // ---
            'config_cars_price.first_miles AS firstMiles',
            'config_cars_price.first_miles_price AS firstMilesPrice',
            'config_cars_price.first_miles_price_active AS firstMilesPriceActive',
            // ---
            'config_cars_price.second_miles AS secondMiles',
            'config_cars_price.second_miles_price AS secondMilesPrice',
            'config_cars_price.second_miles_price_active AS secondMilesPriceActive',
            // ---
            'config_cars_price.third_miles AS thirdMiles',
            'config_cars_price.third_miles_price AS thirdMilesPrice',
            'config_cars_price.third_miles_price_active AS thirdMilesPriceActive',
            // ---
            'config_cars_price.admin_fee_limit_miles AS adminFeeLimitMiles',
            'config_cars_price.admin_fee_type AS adminFeeType',
            'config_cars_price.admin_fee_percentage AS adminFeePercentage',
            'config_cars_price.admin_fee_fixed_amount AS adminFeeFixedAmount',
            'config_cars_price.admin_fee_active AS adminFeeActive',
            //---
            'config_cars_price.pickup_fee_limit_miles AS pickUpFeeLimitMiles',
            'config_cars_price.pickup_fee_type AS pickUpFeeType',
            'config_cars_price.pickup_fee_percentage AS pickUpFeePercentage',
            'config_cars_price.pickup_fee_fixed_amount AS pickUpFeeFixedAmount',
            'config_cars_price.pickup_fee_active AS pickUpFeeActive',
            //---
            'config_cars_price.max_luggages AS maxLuggages',
            'config_cars_price.extra_luggages_price AS extraLuggagesPrice',
            //---
            'config_cars_price.max_passengers AS maxPassengers',
            'config_cars_price.free_passengers_quantity AS freePassengersQuantity',
            'config_cars_price.extra_passengers_price AS extraPassengersPrice',
        ])
        ->join('config_cars_price', 'config_cars_price.car_id = config_cars.car_id')
        ->findAll();

        return $get_list_query;
    }

    public function editCar($data)
    {
        $edit_car_query = $this->update(
            $data->carId,
            [
                'car_quantity' => $data->carQuantity,
                'car_active' => $data->carActive,
            ]
        );

        return $edit_car_query;
    }
}