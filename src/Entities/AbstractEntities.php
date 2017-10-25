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
            'limit'   => $this->pageSize > $limit ? $limit : $this->pageSize,
            'page'    => $currentPage,
            'contain' => $conditions->get('contain', array_keys($this->contain), 'arr'),
        ];

        $this->hoClient->trigger("{$this->target}.find.request", [$this]);

        /** @var Data $response */
        $response = $this->hoClient->apiRequest($apiRequest);
        $firstPageResponse = $response->get('data', [], 'arr');
        $allPages = $response->get('pageCount', 0, 'int');

        if ($allPages > 1 && count($firstPageResponse) < $limit) {
            $result = $this->prepareResults($firstPageResponse);

            for ($requestedPage = 2; $requestedPage <= $allPages; $requestedPage++) {
                $apiRequest['page'] = $requestedPage;

                $response = $this->hoClient->apiRequest($apiRequest);
                $listCurrentStep = $response->get('data', [], 'arr');

                $result += $this->prepareResults($listCurrentStep);
                if (count($result) >= $limit) {
                    break;
                }
            }
        } else {
            $result = $this->prepareResults($firstPageResponse);
        }

        $result = array_slice($result, 0, $limit, true); // Force limit

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
}
