<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
	protected $table = 'roles';
	protected $primaryKey = 'role_id';
    protected $useAutoIncrement = false;

	protected $allowedFields = [
		'role_id',
        'role_desc',
        'role_allowed_routes',
        'role_active',
        'role_created_at',
        'role_updated_at',
	];
}