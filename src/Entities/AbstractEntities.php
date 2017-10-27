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

namespace Unilead\HasOffers\Entities;

use JBZoo\Data\Data;
use function JBZoo\Data\json;
use Unilead\HasOffers\HasOffersClient;

/**
 * Class AbstractEntities
 *
 * @package Unilead\HasOffers\Entities
 */
abstract class AbstractEntities
{
    const DEFAULT_LIMIT = 50000;

    /**
     * @var int
     */
    protected $pageSize = 500;

    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $targetAlias;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $contain = [];

    /**
     * @var array
     */
    protected $methods = [
        'findAll' => 'findAll',
    ];

    /**
     * @var array|null
     */
    protected $defaultFields;

    /**
     * @var array|null
     */
    protected $defaultSort = ['id' => 'desc'];

    /**
     * @param array $conditions
     * @return array
     */
    public function find(array $conditions = [])
    {
        $this->hoClient->lastResponseMode(false);

        $this->hoClient->trigger("{$this->target}.find.before", [$this, &$conditions]);

        $realLimit = $this->getRealLimit($conditions);
        $apiRequest = $this->buildApiRequest($conditions);

        $this->hoClient->trigger("{$this->target}.find.request", [$this, &$apiRequest]);

        /** @var Data $firstResponse */
        $firstPageResponse = $this->hoClient->apiRequest($apiRequest);
        $pageCount = $firstPageResponse->get('pageCount', 0, 'int');
        $result = $this->prepareResults($firstPageResponse->get('data', [], 'arr'));

        if ($pageCount > 1 &&
            ($realLimit > 0 && count($result) < $realLimit)
        ) {
            for ($requestedPage = 2; $requestedPage < $pageCount; $requestedPage++) {
                $apiRequest['page'] = $requestedPage;

                $curStepResponse = $this->hoClient->apiRequest($apiRequest);

                $result += $this->prepareResults($curStepResponse->get('data', [], 'arr'));
                unset($curStepResponse);

                if (count($result) >= $realLimit) {
                    break;
                }
            }
        }

        if ($realLimit) {
            $result = array_slice($result, 0, $realLimit, true); // Force limit
        }

        $this->hoClient->trigger("{$this->target}.find.after", [$this, &$result]);

        $this->hoClient->lastResponseMode(true);

        return $result;
    }

    /**
     * @param array $conditions
     * @return int
     */
    public function count(array $conditions = [])
    {
        // For count prop in response
        $conditions['limit'] = 1;
        $conditions['page'] = 0;

        // For optimize
        $conditions['fields'] = ['id'];
        $conditions['contain'] = [];
        $conditions['sort'] = [];

        $apiRequest = $this->buildApiRequest($conditions);

        $firstResponse = $this->hoClient->apiRequest($apiRequest);
        return (int)$firstResponse['count'];
    }

    /**
     * Setter for HasOffers Client.
     *
     * @param HasOffersClient $hoClient
     *
     * @return $this
     */
    public function setClient(HasOffersClient $hoClient)
    {
        $this->hoClient = $hoClient;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = (int)$pageSize;
        return $this;
    }

    /**
     * @param int $limit
     * @return int
     */
    public function getPageSize($limit = 0)
    {
        if ($limit === 0) {
            return $this->pageSize;
        }

        if ($limit < $this->pageSize) {
            return $limit;
        }

        return $this->pageSize;
    }

    /**
     * @param array $listResult
     * @return array
     */
    protected function prepareResults($listResult)
    {
        $this->hoClient->trigger("{$this->target}.find.prepare.before", [$this]);

        $key = $this->targetAlias ?: $this->target;

        $result = [];
        foreach ($listResult as $itemId => $itemData) {
            $result[$itemId] = $this->hoClient->get($this->className, $itemId, $itemData[$key], $itemData);
        }

        $this->hoClient->trigger("{$this->target}.find.prepare.after", [$this, &$result]);

        return $result;
    }

    /**
     * @param array $conditions
     * @return array
     */
    protected function buildApiRequest(array $conditions)
    {
        $conditionsData = json($conditions);

        $pageSize = $this->getPageSize();
        $realLimit = $this->getRealLimit($conditions);

        $apiRequest = [
            'Method'  => $this->methods['findAll'],
            'Target'  => $this->target,
            'fields'  => $conditionsData->get('fields', $this->defaultFields, 'arr'),
            'filters' => $conditionsData->get('filters', [], 'arr'),
            'sort'    => $conditionsData->get('sort', $this->defaultSort, 'arr'),
            'contain' => $conditionsData->get('contain', array_keys($this->contain), 'arr'),
            'limit'   => $pageSize,
        ];

        if ($realLimit === 0) {
            $apiRequest['limit'] = $pageSize;
        } elseif ($realLimit > $pageSize) {
            $apiRequest['limit'] = $pageSize;
        } elseif ($realLimit < $pageSize) {
            $apiRequest['limit'] = $realLimit;
        }

        return $apiRequest;
    }

    /**
     * @param array $conditions
     * @return int
     */
    protected function getRealLimit(array $conditions)
    {
        return json($conditions)->get('limit', 0, 'int');
    }
}
