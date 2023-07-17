<?php

namespace App\Models;

use CodeIgniter\Model;

class NavigationMenuModel extends Model
{
	protected $table = 'navigation_menu';
	protected $primaryKey = 'nav_id';
    protected $useAutoIncrement = true;

	protected $allowedFields = [
		'nav_title',
        'nav_route',
        'nav_role',
        'nav_icon',
        'nav_active',
        'nav_created_at',
        'nav_updated_at',
	];

    public function getNavItemsByRole($role_id)
    {
        $get_user_query = $this->select([
            'navigation_menu.nav_title AS navTitle',
            'navigation_menu.nav_route AS navRoute',
            'navigation_menu.nav_icon AS navIcon',
        ])
        ->where([
            'navigation_menu.nav_active' => 1,
            'navigation_menu.nav_role' => $role_id,
        ])
        ->findAll();

        return $get_user_query;
    }
}