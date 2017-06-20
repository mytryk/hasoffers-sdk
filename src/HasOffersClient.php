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
use JBZoo\Event\EventManager;
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
        $this->trigger("{$object->getTarget()}.init", [$object]);

        return $object;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function apiRequest(array $data)
    {
        $this->trigger('api.request.before', [$this, &$data]);

        try {
            $this->requestCounter++;

            if ($this->limitCounter > 0 &&
                $this->timeout > 0 &&
                $this->requestCounter % $this->limitCounter === 0
            ) {
                sleep($this->timeout);
                $this->trigger('api.request.sleep', [$this, &$data]);
            }

            $httpClient = new HttpClient([
                'timeout'    => self::HTTP_TIMEOUT,
                'verify'     => false,
                'exceptions' => true
            ]);

            $data = array_merge($data, [
                'NetworkToken' => $this->networkToken
            ]);

            $url = str_replace('__NETWORK_ID__.', $this->networkId . '.', $this->apiUrl);
            $response = $httpClient->request($url, $data, 'get');
            $json = $response->getJSON();

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
            // Rewrite exception
            throw new Exception($httpException->getMessage(), $httpException->getCode(), $httpException);
        }

        $this->trigger('api.request.after', [$this, $json, $response]);
        $result = new JSON($json->find('response.data'));

        return $result;
    }

    /**
     * Setter for external EventManager
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
            $eventName = strtolower("ho.{$eventName}");
            return $this->eManager->trigger($eventName, $arguments, $continueCallback);
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
}
