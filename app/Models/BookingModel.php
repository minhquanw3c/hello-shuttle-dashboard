<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
	protected $table = 'bookings';
	protected $primaryKey = 'booking_id';

	protected $allowedFields = [
        'booking_data',
        'payment_link',
        'payment_status',
        'checkout_session_id',
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
        $clear_booking_query = $this->truncate();

        return $clear_booking_query;
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
}