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
     * @var array
     * @TODO: Move hardcode to child classes
     */
    protected $noCreateObject = ['Conversion'];

    /**
     * @param array $conditions
     * @return array
     */
    public function find(array $conditions = [])
    {
        $this->hoClient->trigger("{$this->target}.find.before", [$this, &$conditions]);

        $conditionData = json($conditions);
        $apiRequest = $this->buildApiRequest($conditionData);

        $this->hoClient->trigger("{$this->target}.find.request", [$this, &$apiRequest]);

        /** @var Data $firstResponse */
        $firstResponse = $this->hoClient->apiRequest($apiRequest);
        $firstPageResponse = $firstResponse->get('data', [], 'arr');

        $pageCount = $firstResponse->get('pageCount', 0, 'int');
        //$totalItemCount = $firstResponse->get('count', 0, 'int');

        $result = $this->prepareResults($firstPageResponse);
        if ($pageCount > 1) {
            for ($requestedPage = 2; $requestedPage <= $pageCount; $requestedPage++) {
                $apiRequest['page'] = $requestedPage;

                $curStepResponse = $this->hoClient->apiRequest($apiRequest);

                $result += $this->prepareResults($curStepResponse->get('data', [], 'arr'));
                unset($curStepResponse);
            }
        }

        if ($limit = $this->getLimit($conditionData, false)) {
            $result = array_slice($result, 0, $limit, true); // Force limit
        }

        $this->hoClient->trigger("{$this->target}.find.after", [$this, &$result]);

        return $result;
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
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return (int)$this->pageSize;
    }

    /**
     * @param array $listResult
     * @return array
     */
    protected function prepareResults(array $listResult)
    {
        $this->hoClient->trigger("{$this->target}.find.prepare.before", [$this]);

        $result = [];
        $key = $this->targetAlias ?: $this->target;

        foreach ($listResult as $itemId => $itemData) {
            $result[$itemId] = $this->hoClient->get($this->className, $itemId, $itemData[$key], $itemData);
        }

        $this->hoClient->trigger("{$this->target}.find.prepare.after", [$this, &$result]);

        return $result;
    }

    /**
     * @param Data $conditions
     * @return array
     */
    protected function buildApiRequest(Data $conditions)
    {
        $apiRequest = [
            'Method'  => $this->methods['findAll'],
            'Target'  => $this->target,
            'fields'  => $conditions->get('fields', $this->defaultFields, 'arr'),
            'filters' => $conditions->get('filters', [], 'arr'),
            'sort'    => $conditions->get('sort', $this->defaultSort, 'arr'),
            'contain' => $conditions->get('contain', array_keys($this->contain), 'arr'),
        ];

        if ($limit = $this->getLimit($conditions, true)) {
            $apiRequest['limit'] = $limit;
            $apiRequest['page'] = 1;
        }

        return $apiRequest;
    }

    /**
     * @param Data $conditions
     * @param bool $getDefault
     * @return int
     */
    protected function getLimit(Data $conditions, $getDefault)
    {
        if ($getDefault) {
            return $conditions->get('limit', self::DEFAULT_LIMIT, 'int');
        }

        return $conditions->get('limit', 0, 'int');
    }
}
