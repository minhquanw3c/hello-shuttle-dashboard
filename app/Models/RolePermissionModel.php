<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
	protected $table = 'roles_permissions';
	protected $primaryKey = 'permission_id';
    protected $useAutoIncrement = true;

	protected $allowedFields = [
		'permission_id',
        'role',
        'allowed_route_pattern',
        'permission_active',
        'permission_created_at',
        'permission_updated_at',
	];
}