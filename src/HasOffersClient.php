<?php
/**
 * Unilead | HasOffers
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace Unilead\HasOffers;

use JBZoo\Data\JSON;
use JBZoo\HttpClient\HttpClient;
use Unilead\HasOffers\Entity\AbstractEntity;

/**
 * Class Request
 * @package Unilead\HasOffers
 */
class HasOffersClient
{
    const HTTP_TIMEOUT    = 30;
    const DEFAULT_API_URL = 'https://__NETWORK_ID__.api.hasoffers.com/Apiv3/json';

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $networkId;

    /**
     * @var string
     */
    protected $networkToken;

    /**
     * HasOffersClient constructor.
     * @param string $networkId
     * @param string $token
     * @param string $apiUrl
     */
    public function __construct($networkId, $token, $apiUrl = self::DEFAULT_API_URL)
    {
        $this->networkId = $networkId;
        $this->networkToken = $token;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param string $modelClassName
     * @param int    $entityId
     * @return AbstractEntity
     * @throws Exception
     */
    public function get($modelClassName, $entityId = null)
    {
        if (class_exists($modelClassName)) {
            $willCreate = $modelClassName;
        } elseif (class_exists(__NAMESPACE__ . '\\Entity\\' . $modelClassName)) {
            $willCreate = __NAMESPACE__ . '\\Entity\\' . $modelClassName;
        } else {
            throw new Exception("Model with class name \"{$modelClassName}\" does not exist.");
        }

        /** @var AbstractEntity $object */
        $object = new $willCreate($entityId);
        $object->setClient($this);

        return $object;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function apiRequest(array $data)
    {
        try {
            $httpClient = new HttpClient([
                'timeout'    => self::HTTP_TIMEOUT,
                'verify'     => false,
                'exceptions' => true
            ]);

            $data = array_merge($data, [
                'NetworkToken' => $this->networkToken
            ]);

            $url = str_replace('__NETWORK_ID__.', $this->networkId . '.', $this->apiUrl);
            $resp = $httpClient->request($url, $data, 'get')->getJSON();

            if ($resp->find('response.status', null, 'int') !== 1) {
                $details = $resp->find('response.errors.0.err_msg') ?: $resp->find('response.errors.0.publicMessage');
                $errorMessage = $resp->find('response.errorMessage');

                if ($details !== $errorMessage) {
                    throw new Exception('HasOffers Error: ' . $errorMessage . ' ' . $details);
                }

                throw new Exception('HasOffers Error: ' . $errorMessage);
            }
        } catch (\Exception $httpException) {
            // Rewrite exception
            throw new Exception($httpException->getMessage(), $httpException->getCode(), $httpException);
        }

        return new JSON($resp->find('response.data'));
    }
}
