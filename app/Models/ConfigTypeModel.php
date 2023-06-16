<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigTypeModel extends Model
{
	protected $table = 'config_types';
	protected $primaryKey = 'config_type_id';

	protected $allowedFields = [
        'config_type_desc',
        'config_type_created_at',
        'config_type_updated_at',
	];

    public function editConfigType($data)
    {
        $edit_query = $this->update([
            'config_type_id' => $data->configTypeId,
            'config_type_desc' => $data->configTypeDesc,
        ]);

        return $edit_query;
    }
}