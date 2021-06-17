<?php

namespace Webkul\Dropship\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Security controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SecurityController extends Controller
{
    /**
     * Valudates the url
     *
     * @return \Illuminate\Http\Response
     */
    public function validateUrl()
    {
        $callback = request()->input('callback');

        $response = response($callback . '(' . json_encode(['success' => true, 'message' => 'Url Validate']) . ')');
        $response->header('Content-Type', 'application/javascript');

        return $response;
    }

    /**
     * Authencate chrome extension user
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticateUser()
    {
        $callback = request()->input('callback');

        $user = request()->input('username');
        $token = request()->input('token');

        $response = ['success' => false];
        if ($user != "" && $token != "") {
            $adminUser = core()->getConfigData('dropship.settings.credentials.username');
            $adminToken = core()->getConfigData('dropship.settings.credentials.token');

            if ($adminUser == $user && $adminToken == $token) {
                $response = [
                        'success' => true,
                        'message' => 'Authentication Successfully'
                    ];
            } else {
                $response['message'] = 'Authentication Error';
            }
        }

        $response = response($callback . '(' . json_encode($response) . ')');
        $response->header('Content-Type', 'application/javascript');

        return $response;
    }
}