<?php
/**
 * Unilead | BM
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     BM
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace Unilead\HasOffers;

use JBZoo\HttpClient\HttpClient;

/**
 * Class Request
 *
 * @package Unilead\HasOffers
 */
class HasOffersClient extends HttpClient
{
    protected $apiUrl;

    protected $networkId;

    protected $networkToken;

    public function __construct($url, $networkId, $token)
    {
        parent::__construct();
        $this->apiUrl = $url;
        $this->networkId = $networkId;
        $this->networkToken = $token;
    }

    public function get($model, $id = null)
    {
        if (!class_exists($model)) {
            throw new Exception('Model with this name does not exist.');
        }

        $object = new $model();
        $object->setHasOffersClient($this)->get($id);
        return $object;
    }

    public function apiRequest(array $data = [])
    {
        $httpClient = new HttpClient([
            'driver'     => 'auto',
            'timeout'    => 10,
            'verify'     => true,
            'exceptions' => true
        ]);

        $data = array_merge($data, [
            'NetworkToken' => $this->networkToken
        ]);

        $url = 'https://' . $this->networkId . '.' . $this->apiUrl;

        $response = $httpClient->request($url, $data, 'get')->getJSON();

        if ($response->find('response.status') !== 1) {
            $error = $response->find('response.errors.0.err_msg')
                ? $response->find('response.errors.0.err_msg')
                : $response->find('response.errors.0.publicMessage');
            $message = $response->find('response.errorMessage');
            throw new Exception($error . ' ' . $message);
        }

        return $response->find('response.data');
    }
}
