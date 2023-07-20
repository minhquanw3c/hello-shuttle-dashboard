<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserAuth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $logged_in = session()->has('logged_in');
        $request = \Config\Services::request();

        if ($logged_in) {
            $user_model = model(UserModel::class);
            $user = session()->get('logged_in');
            $user = $user_model->getUserByEmail($user['username']);

            if (count($user) == 0) {
                session()->destroy();
                return redirect()->to(base_url('/'));
            }

            $user = $user[0];

            if (!($user['userRole'] === 'admin')) {
                $request_path = $request->getPath();
                $allowed_routes = explode("|", $user['userAllowedRoutes']);
                $api_pattern = '/^api/';

                if (!in_array($request_path, $allowed_routes)) {
                    return preg_match($api_pattern, $request_path) ? service('response')->setStatusCode(401) : redirect()->to(base_url('bookings'));
                }
            }
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
