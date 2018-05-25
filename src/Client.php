<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers;

use JBZoo\Event\EventManager;
use JBZoo\HttpClient\HttpClient;
use function JBZoo\Data\json;
use JBZoo\Data\JSON;
use JBZoo\Data\Data;
use Item8\HasOffers\Entities\AbstractEntities;
use Item8\HasOffers\Entity\AbstractEntity;
use function JBZoo\PHPUnit\httpRequest;

/**
 * Class Request
 *
 * @package Item8\HasOffers
 */
class Client extends HasOffersClient
{
    public const DEFAULT_API_URL = 'https://__NETWORK_ID__.api.hasoffers.com/Apiv3/json';

    /**
     * @var string
     */
    protected $networkId;

    /**
     * @var string
     */
    protected $networkToken;

    public function setAuth($networkId, $token, $apiUrl = self::DEFAULT_API_URL)
    {
        $this->networkId = $networkId;
        $this->networkToken = $token;
        $this->apiUrl = $apiUrl;
    }

    public function prepareRequest($url, array $requestParams)
    {
        $httpClientParams = [
            'timeout'    => self::HTTP_TIMEOUT,
            'verify'     => true,
            'exceptions' => true,
            'auth'       => $this->httpAuth,
        ];

        $requestParams = array_merge($requestParams, [
            'NetworkToken' => $this->networkToken,
            'NetworkId'    => $this->networkId,
        ]);

        // Fix limits
        if (isset($requestParams['limit']) && (int)$requestParams['limit'] === 0) {
            unset($requestParams['limit']);
        }

        return (new HttpClient($httpClientParams))->request(
            $url,
            $requestParams,
            'GET',
            $httpClientParams
        );
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return str_replace('__NETWORK_ID__.', $this->networkId . '.', $this->apiUrl);
    }
}
