<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Request;

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;

/**
 * Class ClientApi
 *
 * @package Item8\HasOffers
 */
class ClientApi extends AbstractRequest
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

    /**
     * @param int|string $networkId
     * @param string     $networkToken
     * @param string     $apiUrl
     */
    public function setAuth($networkId, $networkToken, $apiUrl = self::DEFAULT_API_URL): void
    {
        $this->networkId = $networkId;
        $this->networkToken = $networkToken;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @inheritdoc
     */
    public function getResponse($url, array $requestParams): Response
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
    public function getApiUrl(): string
    {
        return str_replace('__NETWORK_ID__.', $this->networkId . '.', $this->apiUrl);
    }
}
