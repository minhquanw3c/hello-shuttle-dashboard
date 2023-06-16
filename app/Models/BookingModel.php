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
	];

    public function getBookingsList()
    {
        $get_list_query = $this->select([
            'bookings.booking_id AS bookingId',
            'bookings.booking_created_at AS bookingCreatedAt',
            'payment_status.payment_status_desc AS bookingPaymentStatus',
        ])
        ->join('payment_status', 'payment_status.payment_status_id = bookings.payment_status')
        ->findAll();

        return $get_list_query;
    }
}