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

namespace Unilead\HasOffers\Entity;

use Unilead\HasOffers\Exception;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\Traits\Data;

/**
 * Class AbstractEntity
 *
 * @property string $target
 * @property array  $contain
 *
 * @package Unilead\HasOffers
 */
abstract class AbstractEntity
{
    use Data;

    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    /**
     * @var int
     */
    protected $objectId;

    /**
     * @var array
     */
    protected $related;

    /**
     * Entity constructor.
     * @param int   $entityId
     * @param array $data
     */
    public function __construct($entityId = null, array $data = [])
    {
        $this->objectId = (int)$entityId;
        $this->bindData($data);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function reload()
    {
        if ($this->objectId <= 0) {
            throw new Exception("Can't load info from HasOffers. Entity Id not set for \"{$this->target}\"");
        }

        if (!$this->target) {
            throw new Exception("Undefined target alias for entity \"{$this->objectId}\"");
        }

        $data = $this->hoClient->apiRequest([
            'Target'  => $this->target,
            'Method'  => 'findById',
            'id'      => $this->objectId,
            'contain' => $this->contain,
        ]);

        $this->bindData($data[$this->target]);

        if (count($this->contain) > 0) {
            $this->createRelated($data);
        }

        return $this;
    }

    /**
     * @param $data
     */
    protected function createRelated($data)
    {
        foreach ($this->contain as $objectName => $className) {
            $this->related[$objectName] = new $className($data[$objectName], $this);
        }
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function save()
    {
        if ($this->objectId) {
            $data = $this->hoClient->apiRequest([
                'Method'        => 'update',
                'Target'        => $this->target,
                'data'          => $this->data,
                'return_object' => 1,
            ]);
        } else {
            $data = $this->hoClient->apiRequest([
                'Method'        => 'create',
                'Target'        => $this->target,
                'data'          => $this->data,
                'return_object' => 1,
            ]);
        }

        $this->bindData($data[$this->target]);

        return $this;
    }

    /**
     * Setter for HasOffers Client.
     * @param HasOffersClient $hoClient
     * @return $this
     */
    public function setClient(HasOffersClient $hoClient)
    {
        $this->hoClient = $hoClient;
        return $this;
    }
}
