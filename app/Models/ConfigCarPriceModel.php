<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigCarPriceModel extends Model
{
	protected $table = 'config_cars_price';
	protected $primaryKey = 'car_id';

	protected $allowedFields = [
        'open_door_price',
        'first_miles',
        'first_miles_price',
        'first_miles_price_active',
        'second_miles',
        'second_miles_price',
        'second_miles_price_active',
        'third_miles',
        'third_miles_price',
        'third_miles_price_active',
        'admin_fee_limit_miles',
        'admin_fee_type',
        'admin_fee_percentage',
        'admin_fee_fixed_amount',
        'admin_fee_active',
        'pickup_fee_limit_miles',
        'pickup_fee_type',
        'pickup_fee_percentage',
        'pickup_fee_fixed_amount',
        'pickup_fee_active',
        'max_luggages',
        'free_luggages_quantity',
        'extra_luggages_price',
        'max_passengers',
        'free_passengers_quantity',
        'extra_passengers_price',
	];

    public function editCarPriceConfig($data)
    {
        $edit_query = $this->update(
            $data->carId,
            [
                'open_door_price' => $data->openDoorPrice,
                //---
                'first_miles' => $data->firstMiles,
                'first_miles_price' => $data->firstMilesPrice,
                'first_miles_price_active' => $data->firstMilesPriceActive,
                //---
                'second_miles' => $data->secondMiles,
                'second_miles_price' => $data->secondMilesPrice,
                'second_miles_price_active' => $data->secondMilesPriceActive,
                //---
                'third_miles' => $data->thirdMiles,
                'third_miles_price' => $data->thirdMilesPrice,
                'third_miles_price_active' => $data->thirdMilesPriceActive,
                //---
                'admin_fee_limit_miles' => $data->adminFeeLimitMiles,
                'admin_fee_type' => $data->adminFeeType,
                'admin_fee_percentage' => $data->adminFeePercentage,
                'admin_fee_fixed_amount' => $data->adminFeeFixedAmount,
                'admin_fee_active' => $data->adminFeeActive,
                //---
                'pickup_fee_limit_miles' => $data->pickUpFeeLimitMiles,
                'pickup_fee_type' => $data->pickUpFeeType,
                'pickup_fee_percentage' => $data->pickUpFeePercentage,
                'pickup_fee_fixed_amount' => $data->pickUpFeeFixedAmount,
                'pickup_fee_active' => $data->pickUpFeeActive,
                //---
                'max_luggages' => $data->maxLuggages,
                'free_luggages_quantity' => $data->freeLuggagesQuantity,
                'extra_luggages_price' => $data->extraLuggagesPrice,
                //---
                'max_passengers' => $data->maxPassengers,
                'free_passengers_quantity' => $data->freePassengersQuantity,
                'extra_passengers_price' => $data->extraPassengersPrice,
            ]
        );

        return $edit_query;
    }
}