<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;

	protected $allowedFields = [
        'user_id',
		'user_email',
        'user_hashed_password',
        'user_first_name',
        'user_last_name',
        'user_phone',
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
            'users.user_phone AS userPhone',
            'CONCAT(users.user_last_name, " ", users.user_first_name) AS userFullName',
            'users.user_role AS userRole',
            'GROUP_CONCAT(roles_permissions.allowed_route_pattern SEPARATOR "|") AS userAllowedRoutes',
            'roles.default_dashboard_route AS defaultDashboardRoute',
        ])
        ->join('roles_permissions', 'roles_permissions.role = users.user_role')
        ->join('roles', 'roles.role_id = users.user_role')
        ->where([
            'users.user_active' => 1,
            'roles_permissions.permission_active' => 1,
            'users.user_email' => $user_email,
            'roles.role_active' => 1,
        ])
        ->groupBy([
            'users.user_email',
            'users.user_role',
        ])
        ->findAll();

        return $get_user_query;
    }

    public function getUsers($excluded_roles)
    {
        $query = $this->select([
            'users.user_id AS userId',
            'users.user_email AS userEmail',
            'users.user_first_name AS userFirstName',
            'users.user_last_name AS userLastName',
            'users.user_phone AS userPhone',
            'users.user_created_at AS userCreatedAt',
            'users.user_active AS userActive',
            'users.user_role AS userRole',
        ])
        ->whereNotIn('users.user_role', $excluded_roles)
        ->findAll();

        return $query;
    }

    public function updateUserById($user_id, $data)
    {
        $update_query = $this->update(
            $user_id,
            $data
        );

        return $update_query;
    }
}