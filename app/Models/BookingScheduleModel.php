<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingScheduleModel extends Model
{
	protected $table = 'booking_schedules';
	protected $primaryKey = 'schedule_id';
    protected $useAutoIncrement = true;

	protected $allowedFields = [
        'booking_id',
		'car_id',
        'scheduled_date',
        'scheduled_time',
        'estimated_complete_date',
        'estimated_complete_time',
        'schedule_active',
	];

    public function getAvailableCarsForDate($date)
    {
        $get_list_query = $this->select([
            'config_cars.car_id AS carId',
            'config_cars.car_name AS carName',
            'config_cars.car_quantity - COUNT(booking_schedules.booking_id) AS availableCars',
            'config_cars.car_image AS carImage',
        ])
        ->join('config_cars', 'config_cars.car_id = booking_schedules.car_id AND booking_schedules.scheduled_date = "' . $date . '"', 'right')
        ->groupBy('config_cars.car_id')
        // ->having('availableCars > 0')
        ->findAll();

        return $get_list_query;
    }

    public function createBookingSchedule($booking_schedules)
    {
        $create_schedules_query = $this->insertBatch($booking_schedules);

        return $create_schedules_query;
    }

    public function updateBookingScheduleById($id)
    {
        $update_query = $this->update(
            $id,
            $data
        );

        return $update_query;
    }
}