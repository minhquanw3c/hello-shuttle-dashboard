<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PasswordManagerController extends BaseController
{
    public function changePasswordGateway()
    {
        $request_data = $this->request->getJsonVar('form');

        $validatation_data = $this->validateChangePassword($request_data);

        if ($validatation_data['result'] === false) {
            return $this->response->setJSON($validatation_data);
        }

        $encrypted_password = $this->encryptPassword($request_data->password);

        $user_model = model(UserModel::class);

        $update_data = [
            'user_hashed_password' => $encrypted_password
        ];

        $update_result = $user_model->updateUserById(
            $request_data->userId,
            $update_data
        );

        $response = [
            'messages' => $update_result ? 'Password is updated successfully' : 'There are errors occured',
            'result' => $update_result
        ];

        return $this->response->setJSON($response);
    }

    public function validateChangePassword($data)
    {
        $response = [
            'result' => true,
            'messages' => [],
        ];

        $validation = \Config\Services::validation();

        $validation->setRules([
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password is required',
                ],
            ],
            'confirmNewPassword' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Confirm password is required',
                ],
            ],
        ]);

        $password_validity = $validation->run(get_object_vars($data));

        if ($password_validity === false) {
            $response['result'] = false;
            $response['messages'] = $validation->getErrors();

            return $response;
        }

        return $response;
    }

    public function encryptPassword($data)
    {
        $config = new \Config\Encryption();
        $config->key = 'qnHpj9OG7AQN8S3YvrkKzgiRX0Z5PWaDCcu';

        $encrypter = \Config\Services::encrypter($config);

        $encrypted_password = base64_encode($encrypter->encrypt($data));

        return $encrypted_password;
    }

    public function decryptPassword($data)
    {
        $config = new \Config\Encryption();
        $config->key = 'qnHpj9OG7AQN8S3YvrkKzgiRX0Z5PWaDCcu';

        $encrypter = \Config\Services::encrypter($config);

        $decrypted_password = base64_decode($encrypter->decrypt($data));

        return $decrypted_password;
    }

    public function verifyPasswordPairMatch($provided_password, $user_password)
    {
        return $provided_password === $user_password;
    }
}
