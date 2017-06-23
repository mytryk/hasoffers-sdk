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

use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\Traits\Data;

/**
 * Class AbstractEntity
 *
 * @package Unilead\HasOffers\Entity
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
     * @var array
     */
    protected $contain = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $excludeKeys = [];

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $targetAlias;

    /**
     * Entity constructor.
     *
     * @param int   $objectId
     * @param array $data
     */
    public function __construct($objectId = null, array $data = [])
    {
        $this->objectId = (int)$objectId;
        $this->bindData($data);
        //$this->createRelated($data);
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

        $this->hoClient->trigger("{$this->target}.reload.before", [$this]);

        $data = $this->hoClient->apiRequest([
            'Target'  => $this->target,
            'Method'  => $this->methods['get'],
            'id'      => $this->objectId,
            'contain' => array_keys($this->contain),
        ]);

        $key = $this->targetAlias ?: $this->target;
        $this->bindData($data[$key]);

        if (count($this->contain) > 0) {
            $this->createRelated($data);
        }

        $this->hoClient->trigger("{$this->target}.reload.after", [$this]);

        return $this;
    }

    /**
     * @param $data
     */
    protected function createRelated($data)
    {
        foreach ($this->contain as $objectName => $className) {
            $objectData = $data[$objectName];

            $this->hoClient->trigger(
                "{$this->target}.related.{$objectName}.init.before",
                [$this, &$objectData]
            );

            $this->related[$objectName] = new $className($objectData, $this);

            $this->hoClient->trigger(
                "{$this->target}.related.{$objectName}.init.after",
                [$this, $this->related[$objectName]]
            );
        }
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function save()
    {
        $isNew = !$this->objectId;
        $this->hoClient->trigger("{$this->target}.save.before", [$this, $isNew]);

        if ($isNew) {
            $data = $this->hoClient->apiRequest([
                'Method'        => $this->methods['create'],
                'Target'        => $this->target,
                'data'          => $this->removeExcludeKeys($this->data),
                'return_object' => 1,
            ]);
        } else {
            $dataRequest = $this->removeExcludeKeys($this->data);
            $dataRequest['id'] = $this->objectId;

            $data = $this->hoClient->apiRequest([
                'Method'        => $this->methods['update'],
                'Target'        => $this->target,
                'data'          => $dataRequest,
                'id'            => $this->objectId,
                'return_object' => 1,
            ]);
        }

        $key = $this->targetAlias ?: $this->target;
        $this->bindData($data[$key]);
        $this->objectId = $data[$key]['id'];

        $this->hoClient->trigger("{$this->target}.save.after", [$this, $isNew]);

        return $this;
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
     * @param array $data
     *
     * @return mixed
     */
    private function removeExcludeKeys($data)
    {
        if (empty($this->excludeKeys)) {
            return $data;
        }

        foreach ($this->excludeKeys as $value) {
            unset($data[$value]);
        }

        return $data;
    }
}
