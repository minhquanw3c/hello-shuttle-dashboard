<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
	protected $table = 'bookings';
	protected $primaryKey = 'booking_id';

	protected $allowedFields = [
        'booking_id',
        'booking_ref_no',
		'booking_data',
        'booking_status',
        'payment_link',
        'payment_link_id',
        'payment_status',
        'checkout_session_id',
        'cancel_session_id',
        'booking_created_at',
        'booking_updated_at',
        'booked_by_customer',
	];

    public function getBookingsList($user_id = null)
    {
        if ($user_id) {
            $get_list_query = $this->select([
                'bookings.booking_id AS bookingId',
                'bookings.booking_created_at AS bookingCreatedAt',
                'bookings.booking_data AS bookingData',
                'payment_status.payment_status_desc AS bookingPaymentStatus',
                'CONCAT(users.user_first_name, " ", users.user_last_name) AS customerFullName',
                'users.user_email AS customerEmail',
                'users.user_first_name AS customerFirstName',
                'users.user_last_name AS customerLastName',
                'users.user_phone AS customerPhone',
                'booking_status.booking_status_desc AS bookingStatus',
                'bookings.booking_ref_no AS bookingRefNo',
                'bookings.cancel_session_id AS bookingCancelSessionId',
            ])
            ->join('users', 'users.user_id = bookings.booked_by_customer')
            ->join('payment_status', 'payment_status.payment_status_id = bookings.payment_status')
            ->join('booking_status', 'booking_status.booking_status_id = bookings.booking_status')
            ->where('users.user_id', $user_id)
            ->findAll();
        } else {
            $get_list_query = $this->select([
                'bookings.booking_id AS bookingId',
                'bookings.booking_created_at AS bookingCreatedAt',
                'bookings.booking_data AS bookingData',
                'payment_status.payment_status_desc AS bookingPaymentStatus',
                'CONCAT(customers.first_name, " ", customers.last_name) AS customerFullName',
                'customers.email AS customerEmail',
                'customers.first_name AS customerFirstName',
                'customers.last_name AS customerLastName',
                'customers.phone AS customerPhone',
                'booking_status.booking_status_desc AS bookingStatus',
                'bookings.booking_ref_no AS bookingRefNo',
                'bookings.cancel_session_id AS bookingCancelSessionId',
            ])
            ->join('payment_status', 'payment_status.payment_status_id = bookings.payment_status')
            ->join('booking_status', 'booking_status.booking_status_id = bookings.booking_status')
            ->join('customers', 'customers.customer_id = bookings.booked_by_customer')
            ->findAll();
        }

        return $get_list_query;
    }

    public function clearBookings()
    {
        $booking_schedule_model = model(BookingScheduleModel::class);

        $clear_bookings_query = $this->truncate();
        $clear_booking_schedules_query = $booking_schedule_model->truncate();

        return $clear_bookings_query && $clear_booking_schedules_query;
    }

    public function updateBookingById($booking_id, $data)
    {
        $update_query = $this->update(
            $booking_id,
            $data
        );

        return $update_query;
    }

    public function getColumnValueByKeys($booking_id, $column_key)
    {
        $retrieve_query = $this->select($column_key)->find($booking_id)[$column_key];

        return $retrieve_query;
    }

    public function getBookingById($booking_id, $select_fields = [])
    {
        $query = $this->select("*")->where('booking_id', $booking_id)->first();

        if (count($select_fields) > 0) {
            $query = $this->select(implode(", ", $select_fields))->where('booking_id', $booking_id)->first();
        }

        return $query;
    }
}