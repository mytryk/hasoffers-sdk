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
    const DEFAULT_LIMIT = 1000;

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
    protected $className;

    /**
     * @var array
     */
    protected $contain = [];

    /**
     * @param array $conditions
     * @return array
     */
    public function find(array $conditions = [])
    {
        $this->hoClient->trigger("{$this->target}.find.before", [$this, &$conditions]);

        $conditions = new Data($conditions);

        /** @var array $response */
        $response = $this->hoClient->apiRequest([
            'Target'  => $this->target,
            'Method'  => 'findAll',
            'fields'  => $conditions->get('fields', [], 'arr'),
            'filters' => $conditions->get('filters', [], 'arr'),
            'sort'    => $conditions->get('sort', [], 'arr'),
            'limit'   => $conditions->get('limit', self::DEFAULT_LIMIT, 'int'),
            'page'    => $conditions->get('page', 0, 'int'),
            'contain' => $conditions->get('contain', $this->contain, 'arr'),
        ])->get('data', [], 'arr');

        $result = [];
        foreach ($response as $itemId => $itemData) {
            $result[$itemId] = $this->hoClient->get($this->className, $itemId);
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
}
