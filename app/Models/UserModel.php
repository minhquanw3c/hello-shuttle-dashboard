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
            'users.user_id AS userId',
            'users.user_email AS userEmail',
            'users.user_hashed_password AS userPassword',
            'users.user_first_name AS userFirstName',
            'users.user_last_name AS userLastName',
            'roles.role_id AS userRole',
            'roles.role_allowed_routes AS userAllowedRoutes',
        ])
        ->join('roles', 'roles.role_id = users.user_role')
        ->where([
            'users.user_active' => 1,
            'roles.role_active' => 1,
            'users.user_email' => $user_email,
        ])
        ->findAll();

        return $get_user_query;
    }
}