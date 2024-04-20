<?php

namespace App\Models;

use CodeIgniter\Model;

class CarModel extends Model
{
	protected $table = 'config_cars';
	protected $primaryKey = 'car_id';

	protected $allowedFields = [
        'car_id',
        'car_name',
        'car_seats_capacity',
        'car_quantity',
        'car_editable',
        'car_active',
        'car_image',
        'config_car_created_at',
        'config_car_updated_at',
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
            'config_cars_price.free_luggages_quantity AS freeLuggagesQuantity',
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

    public function createCar($data)
    {
        $car_data = [
            'car_id' => $data->carId,
            'car_name' => $data->carName,
            'car_seats_capacity' => $data->carSeats,
            'car_quantity' => $data->carQuantity,
            'car_active' => $data->carActive,
            'car_editable' => 1,
        ];

        $insert_car_result = $this->insert($car_data, false);

        return $insert_car_result;
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

    public function resetCarConfigurations()
    {
        $drop_query = $this->db->query("DROP TABLE IF EXISTS `config_cars`;");
        $create_query = $this->db->query("
            CREATE TABLE IF NOT EXISTS `config_cars` (
                `car_id` varchar(255) NOT NULL,
                `car_name` varchar(255) NOT NULL,
                `car_seats_capacity` int(50) NOT NULL,
                `car_quantity` int(50) NOT NULL,
                `config_car_created_at` timestamp NULL DEFAULT NULL,
                `config_car_updated_at` timestamp NULL DEFAULT NULL,
                `car_active` int(5) NOT NULL DEFAULT 1,
                `car_editable` int(5) NOT NULL DEFAULT 1,
                `car_image` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`car_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
        $seed_data_query = $this->db->query("
            INSERT INTO `config_cars` (`car_id`, `car_name`, `car_seats_capacity`, `car_quantity`, `config_car_created_at`, `config_car_updated_at`, `car_active`, `car_editable`, `car_image`) VALUES
            ('mn-van', 'Minivan Luxury', 5, 1, '2023-05-29 10:38:57', '2023-05-29 10:38:57', 1, 1, 'vehicle-minivan.png'),
            ('sdn', 'Exec Sedan', 4, 2, '2023-05-31 10:36:17', '2023-05-31 10:36:17', 1, 1, 'vehicle-sedan.png'),
            ('suv', 'Exec SUV', 6, 1, '2023-05-29 10:39:22', '2023-05-29 10:39:22', 1, 1, 'vehicle-suv.png'),
            ('tt-psgr', 'Van (7 Pax)', 10, 1, '2023-05-29 10:38:57', '2023-05-29 10:38:57', 1, 1, 'vehicle-passenger.png'),
            ('tt-psgr-nine-pax', 'Van (9 Pax)', 9, 1, '2023-05-29 10:38:57', '2023-05-29 10:38:57', 1, 1, 'vehicle-passenger.png');
        ");

        return $drop_query && $create_query && $seed_data_query;
    }
}