<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
	protected $table = 'coupons';
	protected $primaryKey = 'coupon_id';

	protected $allowedFields = [
        'coupon_id',
		'coupon_code',
        'discount_amount',
        'is_percentage',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
	];

    public function createCoupon($data)
    {
        $save_query = $this->insert($data, false);

        return $save_query;
    }

    public function getCoupons()
    {
        $get_coupons_query = $this->select([
            'coupons.coupon_id AS couponId',
            'coupons.coupon_code AS couponCode',
            'coupons.discount_amount AS couponDiscountAmount',
            'coupons.is_percentage AS couponIsPercentage',
            'coupons.start_date AS couponStartDate',
            'coupons.end_date AS couponEndDate',
        ])
        ->findAll();

        return $get_coupons_query;
    }

    // public function getBookingById($booking_id)
    // {
    //     $get_booking_query = $this->select([
    //         'bookings.booking_id AS bookingId',
    //         'bookings.booking_data AS bookingData',
    //         'bookings.booking_status AS bookingStatusId',
    //         'bookings.payment_link_id AS bookingPaymentLinkId',
    //         'bookings.payment_status AS bookingPaymentStatus',
    //         'bookings.checkout_session_id AS bookingCheckoutSessionId',
    //         'bookings.booking_created_at AS bookingCreatedAt',
    //     ])
    //     ->where('booking_id', $booking_id)
    //     ->findAll();

    //     return $get_booking_query;
    // }

    public function updateCouponById($coupon_id, $data)
    {
        $update_query = $this->update(
            $coupon_id,
            $data
        );

        return $update_query;
    }
}