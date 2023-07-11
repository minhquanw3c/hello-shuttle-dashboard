<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'user_email';
    protected $useAutoIncrement = true;

	protected $allowedFields = [
		'user_email',
        'user_hashed_password',
        'user_first_name',
        'user_last_name',
        'user_active',
        'user_created_at',
        'user_updated_at',
	];

    public function createUser($data)
    {
        $create_user_query = $this->insert($data, false);

        return $create_user_query;
    }

    public function getUserByEmail($user_email)
    {
        $get_user_query = $this->select([
            'user_id AS userId',
            'user_email AS userEmail',
            'user_hashed_password AS userPassword',
            'user_first_name AS userFirstName',
            'user_last_name AS userLastName',
        ])
        ->where([
            'user_active' => 1,
            'user_email' => $user_email
        ])
        ->findAll();

        return $get_user_query;
    }
}