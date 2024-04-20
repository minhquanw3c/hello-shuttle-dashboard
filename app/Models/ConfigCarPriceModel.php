<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigCarPriceModel extends Model
{
	protected $table = 'config_cars_price';
	protected $primaryKey = 'car_id';

	protected $allowedFields = [
        'car_id',
        'open_door_price',
        #-----------------
        'first_miles',
        'first_miles_price',
        'first_miles_price_active',
        #-----------------
        'second_miles',
        'second_miles_price',
        'second_miles_price_active',
        #-----------------
        'third_miles',
        'third_miles_price',
        'third_miles_price_active',
        #-----------------
        'admin_fee_limit_miles',
        'admin_fee_type',
        'admin_fee_percentage',
        'admin_fee_fixed_amount',
        'admin_fee_active',
        #-----------------
        'pickup_fee_limit_miles',
        'pickup_fee_type',
        'pickup_fee_percentage',
        'pickup_fee_fixed_amount',
        'pickup_fee_active',
        #-----------------
        'max_luggages',
        'free_luggages_quantity',
        'extra_luggages_price',
        #-----------------
        'max_passengers',
        'free_passengers_quantity',
        'extra_passengers_price',
	];

    public function createCarPriceConfig($data)
    {
        $insert_data = [
            'car_id' => $data->carId,
            'open_door_price' => $data->openDoorPrice,
            #-----------------
            'first_miles' => $data->firstMiles,
            'first_miles_price' => $data->firstMilesPrice,
            'first_miles_price_active' => $data->firstMilesPriceActive,
            #-----------------
            'second_miles' => $data->secondMiles,
            'second_miles_price' => $data->secondMilesPrice,
            'second_miles_price_active' => $data->secondMilesPriceActive,
            #-----------------
            'third_miles' => $data->thirdMiles,
            'third_miles_price' => $data->thirdMilesPrice,
            'third_miles_price_active' => $data->thirdMilesPriceActive,
            #-----------------
            'admin_fee_limit_miles' => $data->adminFeeLimitMiles,
            'admin_fee_type' => $data->adminFeeType,
            'admin_fee_percentage' => $data->adminFeePercentage,
            'admin_fee_fixed_amount' => $data->adminFeeFixedAmount,
            'admin_fee_active' => $data->adminFeeActive,
            #-----------------
            'pickup_fee_limit_miles' => $data->pickUpFeeLimitMiles,
            'pickup_fee_type' => $data->pickUpFeeType,
            'pickup_fee_percentage' => $data->pickUpFeePercentage,
            'pickup_fee_fixed_amount' => $data->pickUpFeeFixedAmount,
            'pickup_fee_active' => $data->pickUpFeeActive,
            #-----------------
            'max_luggages' => $data->maxLuggages,
            'free_luggages_quantity' => $data->freeLuggagesQuantity,
            'extra_luggages_price' => $data->extraLuggagesPrice,
            #-----------------
            'max_passengers' => $data->maxPassengers,
            'free_passengers_quantity' => $data->freePassengersQuantity,
            'extra_passengers_price' => $data->extraPassengersPrice,
        ];

        $create_price_config_result = $this->insert($insert_data, false);

        return $create_price_config_result;
    }

    public function editCarPriceConfig($data)
    {
        $edit_query = $this->update(
            $data->carId,
            [
                'open_door_price' => $data->openDoorPrice,
                #-----------------
                'first_miles' => $data->firstMiles,
                'first_miles_price' => $data->firstMilesPrice,
                'first_miles_price_active' => $data->firstMilesPriceActive,
                #-----------------
                'second_miles' => $data->secondMiles,
                'second_miles_price' => $data->secondMilesPrice,
                'second_miles_price_active' => $data->secondMilesPriceActive,
                #-----------------
                'third_miles' => $data->thirdMiles,
                'third_miles_price' => $data->thirdMilesPrice,
                'third_miles_price_active' => $data->thirdMilesPriceActive,
                #-----------------
                'admin_fee_limit_miles' => $data->adminFeeLimitMiles,
                'admin_fee_type' => $data->adminFeeType,
                'admin_fee_percentage' => $data->adminFeePercentage,
                'admin_fee_fixed_amount' => $data->adminFeeFixedAmount,
                'admin_fee_active' => $data->adminFeeActive,
                #-----------------
                'pickup_fee_limit_miles' => $data->pickUpFeeLimitMiles,
                'pickup_fee_type' => $data->pickUpFeeType,
                'pickup_fee_percentage' => $data->pickUpFeePercentage,
                'pickup_fee_fixed_amount' => $data->pickUpFeeFixedAmount,
                'pickup_fee_active' => $data->pickUpFeeActive,
                #-----------------
                'max_luggages' => $data->maxLuggages,
                'free_luggages_quantity' => $data->freeLuggagesQuantity,
                'extra_luggages_price' => $data->extraLuggagesPrice,
                #-----------------
                'max_passengers' => $data->maxPassengers,
                'free_passengers_quantity' => $data->freePassengersQuantity,
                'extra_passengers_price' => $data->extraPassengersPrice,
            ]
        );

        return $edit_query;
    }

    public function resetCarPriceConfigurations()
    {
        $drop_query = $this->db->query("DROP TABLE IF EXISTS `config_cars_price`;");
        $create_query = $this->db->query("
            CREATE TABLE IF NOT EXISTS `config_cars_price` (
                `car_id` varchar(255) NOT NULL,
                `open_door_price` decimal(5,2) NOT NULL,
                `first_miles` decimal(5,2) NOT NULL,
                `first_miles_price` decimal(5,2) NOT NULL,
                `first_miles_price_active` tinyint(4) NOT NULL DEFAULT 1,
                `second_miles` decimal(5,2) NOT NULL,
                `second_miles_price` decimal(5,2) NOT NULL,
                `second_miles_price_active` tinyint(4) NOT NULL DEFAULT 1,
                `third_miles` decimal(5,2) NOT NULL,
                `third_miles_price` decimal(5,2) NOT NULL,
                `third_miles_price_active` tinyint(4) NOT NULL DEFAULT 1,
                `admin_fee_limit_miles` decimal(5,2) NOT NULL,
                `admin_fee_type` varchar(100) NOT NULL,
                `admin_fee_percentage` decimal(5,2) DEFAULT 0.00,
                `admin_fee_fixed_amount` decimal(5,2) DEFAULT 0.00,
                `admin_fee_active` tinyint(4) NOT NULL DEFAULT 1,
                `pickup_fee_limit_miles` decimal(5,2) NOT NULL,
                `pickup_fee_type` varchar(50) NOT NULL,
                `pickup_fee_percentage` decimal(5,2) DEFAULT 0.00,
                `pickup_fee_fixed_amount` decimal(5,2) DEFAULT 0.00,
                `pickup_fee_active` tinyint(4) NOT NULL DEFAULT 1,
                `max_luggages` int(10) NOT NULL,
                `free_luggages_quantity` int(10) NOT NULL,
                `extra_luggages_price` decimal(5,2) NOT NULL,
                `max_passengers` int(10) NOT NULL,
                `free_passengers_quantity` int(10) NOT NULL,
                `extra_passengers_price` decimal(5,2) NOT NULL,
                PRIMARY KEY (`car_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        $seed_data_query = $this->db->query("
            INSERT INTO `config_cars_price` (`car_id`, `open_door_price`, `first_miles`, `first_miles_price`, `first_miles_price_active`, `second_miles`, `second_miles_price`, `second_miles_price_active`, `third_miles`, `third_miles_price`, `third_miles_price_active`, `admin_fee_limit_miles`, `admin_fee_type`, `admin_fee_percentage`, `admin_fee_fixed_amount`, `admin_fee_active`, `pickup_fee_limit_miles`, `pickup_fee_type`, `pickup_fee_percentage`, `pickup_fee_fixed_amount`, `pickup_fee_active`, `max_luggages`, `free_luggages_quantity`, `extra_luggages_price`, `max_passengers`, `free_passengers_quantity`, `extra_passengers_price`) VALUES
            ('mn-van', 30.99, 32.00, 25.99, 1, 40.00, 15.99, 1, 50.00, 10.99, 1, 30.00, 'fixed', 3.00, 5.99, 1, 28.00, 'percentage', 4.00, 50.00, 1, 7, 5, 3.99, 7, 2, 9.99),
            ('sdn', 40.99, 35.00, 35.99, 1, 45.00, 25.99, 1, 60.00, 15.99, 1, 35.00, 'fixed', 5.00, 8.99, 1, 3.00, 'fixed', 5.00, 100.00, 1, 6, 2, 5.99, 5, 2, 6.99),
            ('suv', 20.99, 15.00, 45.99, 1, 30.00, 25.99, 1, 40.00, 15.99, 1, 35.00, 'fixed', 4.50, 7.99, 1, 15.00, 'fixed', 15.00, 300.00, 1, 7, 2, 5.99, 8, 2, 15.99),
            ('tt-psgr', 34.99, 10.00, 6.00, 1, 45.00, 2.00, 0, 50.00, 3.00, 0, 100.00, 'fixed', 6.00, 10.00, 1, 30.00, 'fixed', 20.00, 150.00, 1, 16, 7, 10.00, 7, 4, 10.00),
            ('tt-psgr-nine-pax', 55.99, 40.00, 55.99, 1, 45.00, 35.99, 1, 50.00, 25.99, 1, 35.00, 'fixed', 6.00, 8.99, 1, 30.00, 'fixed', 20.00, 150.00, 1, 15, 5, 6.99, 12, 5, 5.99);
        ");

        return $drop_query && $create_query && $seed_data_query;
    }
}