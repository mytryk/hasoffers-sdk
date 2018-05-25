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
class Integrator extends HasOffersClient
{
    public const DEFAULT_INTEGRATOR_API_URL = 'https://integrator-api.hasoffers.com/Apiv3/json';
    public const DEFAULT_INTEGRATOR_API_AUTH_URL = 'https://integrator-auth.hasoffers.com';

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $integratorId;

    /**
     * @var string
     */
    protected $jwtToken;

    public function setAuth(
        $clientId,
        $clientSecret,
        $integratorId,
        $apiUrl = self::DEFAULT_INTEGRATOR_API_URL
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->integratorId = $integratorId;
        $this->apiUrl = $apiUrl;
    }

    public function setJwtToken($jwtToken)
    {
        $this->jwtToken = $jwtToken;
        return $this;
    }

    public function getJwtToken()
    {
        return $this->jwtToken;
    }

    public function prepareRequest($url, array $requestParams)
    {
        $requestParams = array_merge($requestParams, [
            'Format'       => 'json',
            'NetworkId'    => 'item8demo',
            'IntegratorId' => $this->integratorId,
        ]);

        // Fix limits
        if (isset($requestParams['limit']) && (int)$requestParams['limit'] === 0) {
            unset($requestParams['limit']);
        }

        if (empty($this->getJwtToken())) {
            $jwt = httpRequest("{$this->apiUrl}/authorize", (new JSON([
                'client_id'     => trim($this->clientId),
                'client_secret' => trim($this->clientSecret),
                'audience'      => 'BrandAPI'
            ]))->__toString(), 'POST')->getJSON()->get('access_token');

            $this->setJwtToken($jwt);
        }

        $httpClientParams = [
            'timeout'    => self::HTTP_TIMEOUT,
            'verify'     => true,
            'exceptions' => true,
            'auth'       => $this->httpAuth,
            'headers'    => [
                'authorization' => "Bearer {$this->getJwtToken()}"
            ]
        ];

        return (new HttpClient($httpClientParams))->request(
            'https://integrator-api.hasoffers.com/Apiv3/json',
            $requestParams,
            'GET',
            $httpClientParams
        );
    }
}
