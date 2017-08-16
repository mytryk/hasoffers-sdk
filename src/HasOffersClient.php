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

use JBZoo\Event\EventManager;
use JBZoo\HttpClient\HttpClient;
use function JBZoo\Data\json;
use JBZoo\Data\Data;
use Unilead\HasOffers\Entities\AbstractEntities;
use Unilead\HasOffers\Entity\AbstractEntity;

/**
 * Class Request
 *
 * @package Unilead\HasOffers
 */
class HasOffersClient
{
    const HTTP_TIMEOUT    = 30;
    const DEFAULT_API_URL = 'https://__NETWORK_ID__.api.hasoffers.com/Apiv3/json';

    /**
     * @var int
     */
    protected $requestCounter = 0;

    /**
     * @var int
     */
    protected $limitCounter = 0;

    /**
     * @var int
     */
    protected $timeout = 0;

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
     * @var EventManager|null
     */
    protected $eManager;

    /**
     * @var Data
     */
    protected $lastRequest;

    /**
     * @var Data
     */
    protected $lastResponse;

    /**
     * HasOffersClient constructor.
     *
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
     * @param array  $data
     * @param array  $containData
     * @return AbstractEntity
     * @throws Exception
     */
    public function get($modelClassName, $entityId = null, array $data = [], array $containData = [])
    {
        if (class_exists($modelClassName)) {
            $willCreate = $modelClassName;
        } elseif (class_exists(__NAMESPACE__ . '\\Entity\\' . $modelClassName)) {
            $willCreate = __NAMESPACE__ . '\\Entity\\' . $modelClassName;
        } elseif (class_exists(__NAMESPACE__ . '\\Entities\\' . $modelClassName)) {
            $willCreate = __NAMESPACE__ . '\\Entities\\' . $modelClassName;
        } else {
            throw new Exception("HO Model with class name \"{$modelClassName}\" does not exist.");
        }

        /** @var AbstractEntity|AbstractEntities $object */
        $object = new $willCreate($entityId, $data, $containData, $this);
        $object->setClient($this);
        $this->trigger("{$object->getTarget()}.init", [$object, $data]);

        return $object;
    }

    /**
     * @param array $requestParams
     * @param bool  $returnOnlyData
     * @return Data
     * @throws Exception
     */
    public function apiRequest(array $requestParams, $returnOnlyData = true)
    {
        try {
            $this->requestCounter++;

            if ($this->limitCounter > 0 &&
                $this->timeout > 0 &&
                $this->requestCounter % $this->limitCounter === 0
            ) {
                $isSleep = true;
                $this->trigger('api.request.sleep.before', [$this, &$isSleep]);
                if ($isSleep) {
                    sleep($this->timeout);
                }
                $this->trigger('api.request.sleep.after', [$this, $isSleep]);
            }

            $httpClientParams = [
                'timeout'    => self::HTTP_TIMEOUT,
                'verify'     => true,
                'exceptions' => true,
            ];

            $httpClient = new HttpClient($httpClientParams);

            $url = str_replace('__NETWORK_ID__.', $this->networkId . '.', $this->apiUrl);
            $this->lastRequest = $requestParams;
            $this->trigger('api.request.before', [$this, &$requestParams, &$url]);

            $requestParams = array_merge($requestParams, ['NetworkToken' => $this->networkToken]);

            $response = $httpClient->request($url, $requestParams, 'get', $httpClientParams);

            // Prepare response
            $json = $response->getJSON();
            $data = $json->getArrayCopy();
            $data['request']['NetworkToken'] = '*** hidden ***';
            $json = json($data);

            $this->lastResponse = $json;

            $this->trigger('api.request.after', [$this, $json, $response, $requestParams]);

            $apiStatus = $json->find('response.status', null, 'int');
            if ($apiStatus !== 1) {
                $errorMessage = $json->find('response.errorMessage');
                $details = $json->find('response.errors.0.err_msg')
                    ?: $json->find('response.errors.0.publicMessage');

                if ($details !== $errorMessage) {
                    throw new Exception('HasOffers Error (details): ' . $errorMessage . ' ' . $details);
                }

                if ($errorMessage) {
                    throw new Exception('HasOffers Error: ' . $errorMessage);
                }

                throw new Exception('HasOffers Error. Dump of response: ' . print_r($response, true));
            }
        } catch (\Exception $httpException) {
            throw new Exception($httpException->getMessage(), $httpException->getCode(), $httpException);
        }

        return $returnOnlyData ? json($json->find('response.data')) : json($json);
    }

    /**
     * Setter for external EventManager
     *
     * @param EventManager $eManager
     * @return $this
     */
    public function setEventManager(EventManager $eManager)
    {
        $this->eManager = $eManager;
        return $this;
    }

    /**
     * Emits an event.
     *
     * @param string   $eventName
     * @param array    $arguments
     * @param callback $continueCallback
     * @return int|string
     * @throws Exception
     */
    public function trigger($eventName, array $arguments = [], $continueCallback = null)
    {
        if ($this->eManager) {
            return $this->eManager->trigger("ho.{$eventName}", $arguments, $continueCallback);
        }

        return 0;
    }

    /**
     * @param int $limitCounter
     */
    public function setRequestsLimit($limitCounter)
    {
        $this->limitCounter = (int)$limitCounter;
    }

    /**
     * @param int $seconds
     */
    public function setTimeout($seconds)
    {
        $this->timeout = (int)$seconds;
    }

    /**
     * @return int
     */
    public function getRequestCounter()
    {
        return $this->requestCounter;
    }

    /**
     * @return Data
     */
    public function getLastRequest()
    {
        return json($this->lastRequest);
    }

    /**
     * @return Data
     */
    public function getLastResponse()
    {
        return json($this->lastResponse);
    }
}
