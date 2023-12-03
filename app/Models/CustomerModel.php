<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
	protected $table = 'customers';
	protected $primaryKey = 'customer_id';

	protected $allowedFields = [
        'customer_id',
		'first_name',
        'last_name',
        'phone',
        'email',
        'register_account',
        'has_account',
        'created_at',
        'updated_at',
	];

    public function createCustomer($data)
    {
        $save_query = $this->insert($data, false);

        return $save_query;
    }

    public function updateCustomerDetails($customer_id, $data)
    {
        $update_query = $this->update(
            $customer_id,
            $data
        );

        return $update_query;
    }
}