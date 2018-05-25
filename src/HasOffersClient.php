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
abstract class HasOffersClient
{
    public const HTTP_TIMEOUT = 180;

    public const MODE_INTEGRATOR = 'integrator';
    public const MODE_CLIENT     = 'client';

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
     * @var array
     */
    protected $httpAuth = false;

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
     * @var bool
     */
    protected $lastResponseSave = true;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @param mixed  ...$args
     * @return mixed
     */
    abstract public function setAuth(...$args); // $id, $token, $integratorId = null, $apiUrl
    // $clientId, $clientSecret, $integratorId, $apiUrl = self::DEFAULT_INTEGRATOR_API_URL
    // $networkId, $token, null, $apiUrl = self::DEFAULT_API_URL

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
        $this->sleepBeforeRequest();

        $url = $this->getApiUrl(); //todo
        $this->lastRequest = $requestParams;
        $this->trigger('api.request.before', [$this, &$requestParams, &$url]);

        try {
            $response = $this->prepareRequest($url, $requestParams);
        } catch (\Exception $httpException) {
            throw new Exception($httpException->getMessage(), $httpException->getCode(), $httpException);
        }

        // Prepare response
        $json = $response->getJSON();
        $data = $json->getArrayCopy();
        $data['request']['NetworkToken'] = '*** hidden ***';
        $data['request']['NetworkId'] = '*** hidden ***';

        $this->saveLastResponse($data);
        $json = json($data);

        $requestParams['NetworkToken'] = '*** hidden ***';
        $requestParams['NetworkId'] = '*** hidden ***';
        $this->trigger('api.request.after', [$this, $json, $response, $requestParams, $url]);

        $apiStatus = $json->find('response.status', null, 'int');
        if ($apiStatus !== 1) {
            $errorMessage = $json->find('response.errorMessage');
            $details = $json->find('response.errors.0.err_msg') ?: $json->find('response.errors.publicMessage');

            if ($details && $errorMessage && $details !== $errorMessage) {
                throw new Exception('HasOffers Error (details): ' . $errorMessage . ' ' . $details);
            }

            if ($errorMessage) {
                throw new Exception('HasOffers Error Message: ' . $errorMessage);
            }

            if ($details) {
                throw new Exception('HasOffers Error Details: ' . $details);
            }

            throw new Exception('HasOffers Error. Dump of response: ' . print_r($response, true));
        }

        return $returnOnlyData ? json($json->find('response.data')) : json($json);
    }

    // todo: right now this is a request actually
    abstract public function prepareRequest($url, array $requestParams);

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
     * @throws \JBZoo\Event\Exception
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
     * @return $this
     */
    public function setRequestsLimit($limitCounter)
    {
        $this->limitCounter = (int)$limitCounter;
        return $this;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setTimeout($seconds)
    {
        $this->timeout = (int)$seconds;
        return $this;
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
        return $this->lastResponse ? json($this->lastResponse) : null;
    }

    /**
     * @param bool $mode
     * @return $this
     */
    public function lastResponseMode($mode)
    {
        $this->lastResponseSave = (bool)$mode;
        return $this;
    }

    protected function sleepBeforeRequest()
    {
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
    }

    /**
     * @param array $json
     */
    protected function saveLastResponse($json)
    {
        $this->lastResponse = null;
        if ($this->lastResponseSave) {
            $this->lastResponse = $json;
        }
    }

    /**
     * @param string $login
     * @param string $password
     */
    public function setHttpAuth($login, $password)
    {
        $this->httpAuth = [$login, $password];
    }
}
