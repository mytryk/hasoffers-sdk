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
class Request extends HttpClient
{
    protected $apiUrl;

    protected $networkId;

    protected $networkToken;

    /**
     * Setter for API url.
     *
     * @param string $url
     *
     * @return mixed
     */
    public function setApiUrl($url)
    {
        return $this->apiUrl = $url;
    }

    /**
     * Setter for Network Id.
     *
     * @param string $networkId
     *
     * @return mixed
     */
    public function setNetworkId($networkId)
    {
        return $this->networkId = $networkId;
    }

    /**
     * Setter for Network Token.
     *
     * @param string $networkToken
     *
     * @return mixed
     */
    public function setNetworkToken($networkToken)
    {
        return $this->networkToken = $networkToken;
    }

    public function request(array $data)
    {
        $httpClient = new HttpClient([
            'driver'          => 'auto',
            'timeout'         => 10,
            'verify'          => true,
            'exceptions'      => true
        ]);

        $data = array_merge($data, [
            'NetworkToken' => $this->networkToken
        ]);

        $url = 'https://' . $this->networkId . $this->apiUrl;

        // apiUrl = api.hasoffers.com/Apiv3/json

        //example https://id.api.hasoffers.com/Apiv3/json?NetworkToken=token
        //&Target=Affiliate&Method=updatePaymentMethodOther&affiliate_id=1&data[details]=1231&data[affiliate_id]=12312

        $response = $httpClient->request($url, $data, 'get');

        return $response;
    }
}
