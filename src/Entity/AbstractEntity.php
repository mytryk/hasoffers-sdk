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
 * @property string $target
 * @property string $targetAlias
 * @property array  $contain
 * @property array  $methods
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
     *
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

        $this->hoClient->trigger("{$this->target}.reload.before", [$this]);

        $data = $this->hoClient->apiRequest([
            'Target'  => $this->target,
            'Method'  => $this->methods['get'],
            'id'      => $this->objectId,
            'contain' => array_keys($this->contain),
        ]);

        $key = ($this->targetAlias) ? $this->targetAlias : $this->target;
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
        $this->hoClient->trigger("{$this->target}.related.init.before", [$this]);

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

        $this->hoClient->trigger("{$this->target}.related.init.after", [$this]);
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
                'data'          => $this->data,
                'return_object' => 1,
            ]);
        } else {
            $dataRequest = $this->data;
            $dataRequest['id'] = $this->objectId;

            $data = $this->hoClient->apiRequest([
                'Method'        => $this->methods['update'],
                'Target'        => $this->target,
                'data'          => $dataRequest,
                'id'            => $this->objectId,
                'return_object' => 1,
            ]);
        }

        $key = ($this->targetAlias) ? $this->targetAlias : $this->target;
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
}
