<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
	protected $table = 'configurations';
	protected $primaryKey = 'config_id';

	protected $allowedFields = [
        'config_id',
		'config_name',
        'config_value',
        'config_type_code',
        'config_group_code',
        'config_active',
        'config_editable',
        'config_maximum_quantity',
        'config_created_at',
        'config_updated_at',
        'config_countable',
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
            'configurations.config_maximum_quantity AS configMaximumQuantity',
            'configurations.config_countable AS configCountable',
            'config_types.config_type_id AS configTypeId',
            'config_types.config_type_desc AS configTypeName',
            'config_groups.config_group_id AS configGroupId',
            'config_groups.config_group_desc AS configGroupName',
        ])
        ->join('config_types', 'config_types.config_type_id = configurations.config_type_code')
        ->join('config_groups', 'config_groups.config_group_id = configurations.config_group_code')
        ->where('configurations.config_editable', 1)
        ->findAll();

        return $get_list_query;
    }

    public function createConfig($data)
    {
        $save_query = $this->insert($data, false);

        return $save_query;
    }

    public function editConfig($data)
    {
        $edit_query = $this->update(
            $data->configId,
            [
                'config_value' => $data->value,
                'config_maximum_quantity' => $data->maximumQuantity,
                'config_active' => $data->active,
                'config_countable' => $data->countable,
            ]
        );

        return $edit_query;
    }

    public function resetConfigurations()
    {
        $drop_query = $this->db->query("DROP TABLE IF EXISTS `configurations`;");
        $create_query = $this->db->query("
            CREATE TABLE IF NOT EXISTS `configurations` (
                `config_id` varchar(255) NOT NULL,
                `config_name` varchar(255) NOT NULL,
                `config_value` varchar(255) NOT NULL,
                `config_created_at` timestamp NULL DEFAULT NULL,
                `config_updated_at` timestamp NULL DEFAULT NULL,
                `config_type_code` varchar(50) NOT NULL,
                `config_group_code` varchar(50) NOT NULL,
                `config_active` int(5) NOT NULL DEFAULT 1,
                `config_editable` int(5) NOT NULL DEFAULT 1,
                `config_maximum_quantity` int(5) DEFAULT NULL,
                `config_visible` int(5) NOT NULL DEFAULT 1,
                `config_has_tooltip` int(5) NOT NULL DEFAULT 0,
                `config_tooltip_content` varchar(1000) DEFAULT NULL,
                `config_countable` tinyint(5) DEFAULT NULL,
                PRIMARY KEY (`config_id`),
                KEY `config_type` (`config_type_code`),
                KEY `config_group` (`config_group_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
        $seed_data_query = $this->db->query("
            INSERT INTO `configurations` (`config_id`, `config_name`, `config_value`, `config_created_at`, `config_updated_at`, `config_type_code`, `config_group_code`, `config_active`, `config_editable`, `config_maximum_quantity`, `config_visible`, `config_has_tooltip`, `config_tooltip_content`, `config_countable`) VALUES
            ('bk-insurance', 'Booking Insurance', '90.00', '2023-05-23 10:52:27', '2023-05-23 10:52:27', 'cfg-01', 'cfg-gr-prt', 1, 1, 1, 1, 0, NULL, NULL),
            ('cfg-base-location', '10700 Flower Ave, Stanton, CA 90680, United States', 'ChIJg3bJuRgp3YARgxvl7MlMc-w', '2023-10-02 23:29:43', '2023-10-02 23:29:43', 'cfg-txt', 'cfg-gr-sys', 1, 0, NULL, 1, 0, NULL, NULL),
            ('cfg-curbside-pickup', 'Curbside Pickups', '50.00', '2023-05-23 17:53:53', '2023-05-23 17:53:53', 'cfg-01', 'cfg-gr-opt', 1, 1, 1, 1, 1, 'Do not go to the Uber/Lyft/Taxi transportation lot outside the airport. Your driver will be reaching out to you sometime prior to your pickup time vie text and calls. Driver will be outside the airport awaiting your call. You are to contact him once you are outside by the curb in front of your terminal and ready for pick up. He will then drive up to you within minutes to pick you up.', 0),
            ('cfg-email-sdr', 'Email sender address to customers', 'helloshuttle@minhquanle.a2hosted.com', '2023-06-17 07:37:17', '2023-06-17 07:37:17', 'cfg-txt', 'cfg-gr-sys', 1, 0, NULL, 0, 0, NULL, NULL),
            ('cfg-email-sdr-dev', 'Email sender address to customers in DEV', 'minhquanw3c@gmail.com', '2023-07-08 11:15:26', '2023-07-08 11:15:26', 'cfg-txt', 'cfg-gr-sys', 1, 0, NULL, 0, 0, NULL, NULL),
            ('cfg-ivld-frd', 'Invalid full-refund percentage', '80', '2023-06-23 08:21:00', '2023-06-23 08:21:00', 'cfg-02', 'cfg-gr-sys', 1, 1, NULL, 1, 0, NULL, NULL),
            ('cfg-opt-ctzO3', 'Child booster', '0', '2023-08-09 06:42:00', '2023-08-09 06:42:00', 'cfg-01', 'cfg-gr-opt', 1, 1, 3, 1, 0, NULL, 1),
            ('cfg-opt-E1kOI', 'Pets included', '35.00', '2023-07-01 11:47:42', '2023-07-01 11:47:42', 'cfg-01', 'cfg-gr-opt', 1, 1, 1, 1, 0, NULL, 0),
            ('cfg-rfd-tm', 'Valid hours for full-refund', '24', '2023-06-23 02:53:32', '2023-06-23 02:53:32', 'cfg-cpt', 'cfg-gr-sys', 1, 1, NULL, 1, 0, NULL, NULL),
            ('cfg-rg-non-trfh-01', 'Non traffic hours 3.00 a.m to 6.00 a.m', '3-6', '2023-05-25 11:09:01', '2023-05-25 11:09:01', 'cfg-rg', 'cfg-gr-sys', 1, 0, NULL, 1, 0, NULL, NULL),
            ('cfg-rg-non-trfh-02', 'Non traffic hours 9.00 a.m to 2.00 p.m', '9-14', '2023-05-25 11:14:41', '2023-05-25 11:14:41', 'cfg-rg', 'cfg-gr-sys', 1, 0, NULL, 1, 0, NULL, NULL),
            ('cfg-rg-non-trfh-03', 'Non traffic hours 7.00 p.m to 11.00 p.m', '19-23', '2023-05-25 11:14:41', '2023-05-25 11:14:41', 'cfg-rg', 'cfg-gr-sys', 1, 0, NULL, 1, 0, NULL, NULL),
            ('cfg-stripe-key', 'Stripe payment key', 'sk_test_51N5oaiL3WCB4PP1wjgWKk5DIYSyCBHDV9YcnNqFaozUV8qoDeHyqeH6CQ2tgq7VlF7EYckPUYlQ72H64bj7wmtjC00LuWcpiNA', '2023-07-01 10:06:57', '2023-07-01 10:06:57', 'cfg-txt', 'cfg-gr-sys', 1, 0, NULL, 0, 0, NULL, NULL),
            ('cfg-trffh-rate', 'Extra for traffic hours', '10.00', '2023-05-25 10:16:52', '2023-05-25 10:16:52', 'cfg-02', 'cfg-gr-sys', 1, 1, NULL, 1, 0, NULL, NULL),
            ('crst', 'Car seat', '20.00', '2023-05-23 17:26:45', '2023-05-23 17:26:45', 'cfg-01', 'cfg-gr-opt', 1, 1, 5, 1, 0, NULL, 1),
            ('mt-n-grt', 'Meet and greet', '40.00', '2023-05-23 17:53:53', '2023-05-23 17:53:53', 'cfg-01', 'cfg-gr-opt', 1, 1, 1, 1, 1, 'Your driver will be waiting for you inside the airport terminal in front of the baggage claim, holding a sign with your name. There will be an additional $40.00 fee for this service plus parking fees.', 0);
        ");

        return $drop_query && $create_query && $seed_data_query;
    }
}