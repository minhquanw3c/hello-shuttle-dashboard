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
        'user_role',
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
            'CONCAT(users.user_last_name, " ", users.user_first_name) AS userFullName',
            'users.user_role AS userRole',
            'GROUP_CONCAT(roles_permissions.allowed_route_pattern SEPARATOR "|") AS userAllowedRoutes',
        ])
        ->join('roles_permissions', 'roles_permissions.role = users.user_role')
        ->where([
            'users.user_active' => 1,
            'roles_permissions.permission_active' => 1,
            'users.user_email' => $user_email,
        ])
        ->groupBy([
            'users.user_email',
            'users.user_role',
        ])
        ->findAll();

        return $get_user_query;
    }
}