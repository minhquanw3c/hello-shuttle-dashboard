<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ConfigTypeModel;

class ConfigModel extends Model
{
	protected $table = 'configurations';
	protected $primaryKey = 'config_id';

	protected $allowedFields = [
		'config_name',
        'config_value',
        'config_type_code',
        'config_group_code',
        'config_active',
        'config_editable',
        'config_created_at',
        'config_updated_at',
	];

    public function saveConfig($data)
    {
        $save_query = $this->save($data, false);

        return $save_query;
    }

    public function getConfigList()
    {
        $get_list_query = $this->select([
            'configurations.config_id AS configId',
            'configurations.config_name AS configName',
            'configurations.config_value AS configValue',
            'configurations.config_active AS configActive',
            'configurations.config_editable AS configEditable',
            'config_types.config_type_id AS configTypeId',
            'config_types.config_type_desc AS configTypeName',
            'config_groups.config_group_id AS configGroupId',
            'config_groups.config_group_desc AS configGroupName',
        ])
        ->join('config_types', 'config_types.config_type_id = configurations.config_type_code')
        ->join('config_groups', 'config_groups.config_group_id = configurations.config_group_code')
        ->findAll();

        return $get_list_query;
    }

    public function editConfig($data)
    {
        $edit_query = $this->update(
            $data->configId,
            [
                'config_value' => $data->value,
                'config_active' => $data->active,
            ]
        );

        return $edit_query;
    }
}