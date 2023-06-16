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
            'config_cars.car_start_price AS carStartPrice',
            'config_cars.car_active AS carActive',
            'config_cars.car_editable AS carEditable',
        ])
        ->findAll();

        return $get_list_query;
    }

    public function editCar($data)
    {
        $edit_query = $this->update(
            $data->carId,
            [
                'car_quantity' => $data->carQuantity,
                'car_active' => $data->carActive,
                'car_start_price' => $data->carStartPrice,
            ]
        );

        return $edit_query;
    }
}