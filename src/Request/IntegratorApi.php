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

namespace Item8\HasOffers\Request;

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Data\JSON;

/**
 * Class IntegratorApi
 *
 * @package Item8\HasOffers
 */
class IntegratorApi extends AbstractRequest
{
    public const DEFAULT_INTEGRATOR_API_URL      = 'https://integrator-api.hasoffers.com/Apiv3/json';
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

    /**
     * @param string      $clientId
     * @param string      $clientSecret
     * @param null|string $integratorId
     * @param string      $apiUrl
     */
    public function setAuth($clientId, $clientSecret, $integratorId, $apiUrl = self::DEFAULT_INTEGRATOR_API_URL): void
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->integratorId = $integratorId;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Setter for JWT token.
     * @param $jwtToken
     * @return $this
     */
    public function setJwtToken($jwtToken)
    {
        $this->jwtToken = $jwtToken;
        return $this;
    }

    /**
     * Getter for JWT token.
     * @return string
     */
    public function getJwtToken()
    {
        return $this->jwtToken;
    }

    /**
     * Request to HasOffers for new JWT token.
     * @return mixed
     * @throws Exception
     */
    public function requestJwtToken()
    {
        try {
            $jwt = (new HttpClient())->request(
                self::DEFAULT_INTEGRATOR_API_AUTH_URL . '/authorize',
                (new JSON([
                    'client_id'     => trim($this->clientId),
                    'client_secret' => trim($this->clientSecret),
                    'audience'      => 'BrandAPI'
                ]))->__toString(),
                'POST'
            )->getJSON()->get('access_token');

            //todo: check wrong request for JWT

        } catch (\Exception $httpException) {
            throw new Exception(
                ' Can not receive JWT token: ' . $httpException->getMessage(),
                $httpException->getCode(),
                $httpException);
        }

        return $jwt;
    }

    /**
     * @inheritdoc
     */
    public function getResponse($url, array $requestParams): Response
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
            $jwt = $this->requestJwtToken();
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

        try{
            $response = (new HttpClient($httpClientParams))->request(
                $url,
                $requestParams,
                'GET',
                $httpClientParams
            );
        } catch (\Exception $httpException) {
            if (strpos($httpException->getMessage(), 'JWT is not valid or missing') !== false) {
                $jwt = $this->requestJwtToken();
                $this->setJwtToken($jwt);

                $httpClientParams = array_merge_recursive([
                    'headers'    => [
                        'authorization' => "Bearer {$this->getJwtToken()}"
                    ]
                ]);

                return (new HttpClient($httpClientParams))->request(
                    $url,
                    $requestParams,
                    'GET',
                    $httpClientParams
                );
            }
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return self::DEFAULT_INTEGRATOR_API_URL;
    }
}
