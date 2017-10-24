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
    protected $forceFileds;

    /**
     * @var array
     */
    protected $noCreateObject = [
        'Conversion',
    ];

    /**
     * @param array $conditions
     * @return array
     */
    public function find(array $conditions = [])
    {
        $this->hoClient->trigger("{$this->target}.find.before", [$this, &$conditions]);

        $conditions = new Data($conditions);

        $currentPage = 0;

        $limit = $conditions->get('limit', self::DEFAULT_LIMIT, 'int');

        $apiRequest = [
            'Method'  => $this->methods['findAll'],
            'Target'  => $this->target,
            'fields'  => $this->forceFileds ?: $conditions->get('fields', [], 'arr'),
            'filters' => $conditions->get('filters', [], 'arr'),
            'sort'    => $conditions->get('sort', ['id' => 'desc'], 'arr'),
            'limit'   => $this->pageSize,
            'page'    => $currentPage,
            'contain' => $conditions->get('contain', array_keys($this->contain), 'arr'),
        ];

        /** @var Data $response */
        $response = $this->hoClient->apiRequest($apiRequest);
        $listResult = $response->get('data', [], 'arr');
        $allPages = $response->get('pageCount', 0, 'int');

        if (count($listResult) < $limit) {
            for ($requestedPage = 2; $requestedPage <= $allPages; $requestedPage++) {
                $apiRequest['page'] = $requestedPage;

                $response = $this->hoClient->apiRequest($apiRequest);
                $listCurrentStep = $response->get('data', [], 'arr');

                $listResult += $listCurrentStep;
                if (count($listResult) >= $limit) {
                    break;
                }
            }
        }

        $listResult = array_slice($listResult, 0, $limit, true);

        $result = $this->prepareResults($listResult);

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
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @param array $listResult
     * @return array
     */
    protected function prepareResults(array $listResult)
    {
        $result = [];
        $key = $this->targetAlias ?: $this->target;

        foreach ($listResult as $itemId => $itemData) {
            $result[$itemId] = $this->hoClient->get($this->className, $itemId, $itemData[$key], $itemData);
        }

        return $result;
    }
}
