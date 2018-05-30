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
     * @var string
     */
    protected $expireDate;

    /**
     * @var string
     */
    protected $rawToken;

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
     * Getter for raw jwt token response.
     * @return string
     */
    public function getRawToken()
    {
        return $this->rawToken;
    }

    /**
     * Setter for raw jwt token response.
     * @param string $rawToken
     * @return $this
     */
    public function setRawToken($rawToken)
    {
        $this->rawToken = $rawToken;
        return $this;
    }

    /**
     * Setter for JWT token.
     * @param string $jwtToken
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
     * Setter for JWT token expiration date in seconds.
     * @param $expireDate
     * @return $this
     */
    public function setJwtExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;
        return $this;
    }

    /**
     * Getter for JWT token expiration date in seconds.
     * @return string
     */
    public function getJwtExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Request to HasOffers for new JWT token.
     * @return mixed
     * @throws Exception
     */
    public function updateJwtToken()
    {
        try {
            $response = (new HttpClient())->request(
                self::DEFAULT_INTEGRATOR_API_AUTH_URL . '/authorize',
                (new JSON([
                    'client_id'     => trim($this->clientId),
                    'client_secret' => trim($this->clientSecret),
                    'audience'      => 'BrandAPI'
                ]))->__toString(),
                'POST'
            );
        } catch (\Exception $httpException) {
            throw new Exception(
                ' Can not receive JWT token: ' . $httpException->getMessage(),
                $httpException->getCode(),
                $httpException
            );
        }

        $this->setRawToken($response->get('body'));
        $this->setJwtToken($response->getJSON()->get('access_token'));
        $this->setJwtExpireDate($response->getJSON()->get('expires_in'));

        return $this;
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
            $this->updateJwtToken();
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

        $response = new HttpClient($httpClientParams);
        try {
            $response = $this->makeRequest($httpClientParams, $requestParams, $url);
        } catch (\Exception $httpException) {
            if (strpos($httpException->getMessage(), 'JWT is not valid or missing') !== false) {
                $this->updateJwtToken();

                $httpClientParams = array_merge_recursive([
                    'headers' => [
                        'authorization' => "Bearer {$this->getJwtToken()}"
                    ]
                ]);

                return $this->makeRequest($httpClientParams, $requestParams, $url);
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

    /**
     * @param array $httpClientParams
     * @param array $requestParams
     * @param string  $url
     * @return Response
     */
    protected function makeRequest($httpClientParams, $requestParams, $url)
    {
        return (new HttpClient($httpClientParams))->request(
            $url,
            $requestParams,
            'GET',
            $httpClientParams
        );
    }
}
