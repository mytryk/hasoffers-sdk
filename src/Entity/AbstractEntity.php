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

namespace Item8\HasOffers\Entity;

use Item8\HasOffers\Request\AbstractRequest;
use Item8\HasOffers\Traits\DataEntity;

/**
 * Class AbstractEntity
 *
 * @property string id
 *
 * @package Item8\HasOffers\Entity
 */
abstract class AbstractEntity
{
    use DataEntity;

    /**
     * @var AbstractRequest
     */
    protected $hoClient;

    /**
     * @var int
     */
    protected $objectId;

    /**
     * @var array
     */
    protected $containObjects;

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
    protected $excludedKeys = [];

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
     * @param int                  $objectId
     * @param array                $data
     * @param array                $containData
     * @param AbstractRequest|null $hoClient
     */
    public function __construct(
        $objectId = null,
        array $data = [],
        array $containData = [],
        AbstractRequest $hoClient = null
    ) {
        $this->objectId = (int)$objectId;
        $hoClient && $this->setClient($hoClient);

        $this->origData = $data;
        $this->createRelated($containData);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function reload()
    {
        if ($this->objectId <= 0) {
            throw new Exception("Can't load info from HasOffers. Entity Id not set for \"{$this->target}\" for "
                . static::class);
        }

        if (!$this->target) {
            throw new Exception("Undefined target alias for entity \"{$this->objectId}\" " . static::class);
        }

        if (!$this->hoClient) {
            throw new Exception("HasOffers Client is not set for entity \"{$this->objectId}\" " . static::class);
        }

        $this->hoClient->trigger("{$this->target}.reload.before", [$this]);

        $data = $this->hoClient->apiRequest([
            'Target'  => $this->target,
            'Method'  => $this->methods['get'],
            'id'      => $this->objectId,
            'contain' => array_keys($this->contain),
        ]);

        $key = $this->targetAlias ?: $this->target;
        if (!isset($data[$key])) {
            throw new Exception(
                "Key \"{$key}\" not found in HO data, ObjectId=\"{$this->objectId}\": " .
                print_r($data, true)
            );
        }

        $this->origData = (array)$data[$key];

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
            $objectData = array_key_exists($objectName, $data) ? $data[$objectName] : false;

            if (false === $objectData) {
                continue;
            }

            if (property_exists($this, 'hoClient') && $this->hoClient) {
                $this->hoClient->trigger(
                    "{$this->target}.related.{$objectName}.init.before",
                    [$this, &$objectData]
                );
            }

            $this->containObjects[$objectName] = new $className((array)$objectData, $this);

            if (property_exists($this, 'hoClient') && $this->hoClient) {
                $this->hoClient->trigger(
                    "{$this->target}.related.{$objectName}.init.after",
                    [$this, $this->containObjects[$objectName]]
                );
            }
        }
    }

    /**
     * @param array $properies
     * @return $this
     * @throws Exception
     */
    public function save(array $properies = [])
    {
        if (count($properies) !== 0) {
            return $this->mergeData($properies)->save();
        }

        $isNew = !$this->objectId;
        $this->hoClient->trigger("{$this->target}.save.before", [$this, $isNew]);
        $targetKey = $this->targetAlias ?: $this->target;

        if ($isNew) {
            $dataRequest = $this->removeExcludedKeys($this->changedData);
            if (count($dataRequest) === 0) {
                throw new Exception('No data to create new object "' . static::class . '" in HasOffers');
            }

            $data = $this->hoClient->apiRequest([
                'Method'        => $this->methods['create'],
                'Target'        => $this->target,
                'data'          => $dataRequest,
                'return_object' => 1,
            ]);

            $this->hoClient->trigger("{$this->target}.save.after", [$this, $isNew]);
        } else {
            $dataRequest = $this->removeExcludedKeys($this->getChangedFields());
            if (count($dataRequest) > 0) {
                $dataRequest['id'] = $this->objectId;

                $data = $this->hoClient->apiRequest([
                    'Method'        => $this->methods['update'],
                    'Target'        => $this->target,
                    'data'          => $dataRequest,
                    'id'            => $this->objectId,
                    'return_object' => '1',
                ]);

                $this->hoClient->trigger("{$this->target}.save.after", [$this, $isNew]);
            } else {
                $this->reloadIfNeed();
                $data = [$targetKey => $this->origData];
            }
        }

        if (!isset($data[$targetKey])) {
            throw new Exception('Returned object from HasOffers is not found; '
                . static::class
                . '; Data = ' . print_r($data, true));
        }

        $this->origData = (array)$data[$targetKey];
        $this->objectId = $data[$targetKey]['id'];
        $this->changedData = [];

        return $this;
    }

    /**
     * Setter for HasOffers Client.
     *
     * @param AbstractRequest $hoClient
     *
     * @return $this
     */
    public function setClient(AbstractRequest $hoClient)
    {
        $this->hoClient = $hoClient;

        return $this;
    }

    /**
     * Getter for HasOffers Client.
     *
     * @return AbstractRequest
     */
    public function getClient()
    {
        return $this->hoClient;
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
    private function removeExcludedKeys($data)
    {
        if (empty($this->excludedKeys)) {
            return $data;
        }

        foreach ($this->excludedKeys as $value) {
            if (array_key_exists($value, $data)) {
                unset($data[$value]);
            }
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isExist()
    {
        if (!(int)$this->objectId) {
            return false;
        }

        try {
            $this->id;
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return (int)$this->objectId === 0;
    }
}
