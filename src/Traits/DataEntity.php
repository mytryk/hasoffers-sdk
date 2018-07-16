<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Traits;

use Item8\HasOffers\Request\AbstractRequest;
use JBZoo\Data\Data as JBZooData;

/**
 * Class Data
 *
 * @package Item8\HasOffers\Traits
 */
trait DataEntity
{
    /** @var  AbstractRequest */
    protected $hoClient;

    /**
     * @var array
     */
    protected $origData = [];

    /**
     * @var array
     */
    protected $changedData = [];

    /**
     * @return $this
     */
    abstract public function reload();

    /**
     * Check internal state and reload it if need
     */
    public function reloadIfNeed()
    {
        $isPropExists = property_exists($this, 'objectId');
        $isEmpty = count($this->origData) === 0;

        if ($isEmpty && $isPropExists && $this->objectId > 0) { // for entities
            $this->reload();
        }
    }

    /**
     * @param array $newData
     *
     * @return $this
     */
    public function bindData(array $newData)
    {
        if (property_exists($this, 'hoClient') && $this->hoClient) {
            $this->hoClient->trigger("{$this->target}.bind.before", [$this, &$newData, &$this->changedData]);
        }

        foreach (array_keys($newData) as $key) {
            if ('id' === $key || 0 === strpos($key, '_')) {
                unset($newData[$key]);
            }
        }

        $this->changedData = $newData;

        if (property_exists($this, 'hoClient') && $this->hoClient) {
            $this->hoClient->trigger("{$this->target}.bind.after", [$this, &$this->changedData]);
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->bindData(array_merge($this->origData, (array)$data));

        return $this;
    }

    /**
     * @return JBZooData
     */
    public function data()
    {
        $this->reloadIfNeed();

        // TODO: THINK! Remove hardcode!
        if (array_key_exists('access', $this->origData)) {
            unset($this->origData['access']);
        }

        // TODO: THINK! Remove hardcode!
        if (array_key_exists('access', $this->changedData)) {
            unset($this->changedData['access']);
        }

        return new JBZooData(array_merge($this->origData, $this->changedData));
    }

    /**
     * @return array
     */
    public function getChangedFields()
    {
        $this->reloadIfNeed();

        // TODO: THINK! Remove hardcode!
        if (array_key_exists('access', $this->origData)) {
            unset($this->origData['access']);
        }

        // TODO: THINK! Remove hardcode!
        if (array_key_exists('access', $this->changedData)) {
            unset($this->changedData['access']);
        }

        return array_diff_assoc($this->changedData, $this->origData);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this|string
     * @throws Exception
     */
    public function __call($method, array $arguments = [])
    {
        $relatedObjectName = str_replace(['set', 'get'], '', $method);

        if (strpos($method, 'get') === 0) {
            $this->reloadIfNeed();

            if (array_key_exists($relatedObjectName, $this->contain)) {
                if (!array_key_exists($relatedObjectName, (array)$this->containObjects)) {
                    $this->containObjects[$relatedObjectName] = new $this->contain[$relatedObjectName]([], $this);
                }

                return $this->containObjects[$relatedObjectName];
            }
        }

        throw new Exception("Undefined method \"{$method}\" or related object \"{$relatedObjectName}\" in "
            . static::class . " for objectId={$this->objectId}");
    }

    /**
     * @param string $propName
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($propName)
    {
        $this->reloadIfNeed();

        if (!array_key_exists($propName, $this->origData) && !array_key_exists($propName, $this->changedData)) {
            throw new Exception("Undefined property \"{$propName}\" in " . static::class
                . " for objectId={$this->objectId}");
        }

        if (array_key_exists($propName, $this->changedData)) {
            return $this->changedData[$propName];
        }

        return $this->origData[$propName];
    }

    /**
     * @param string $propName
     * @param mixed  $value
     * @throws Exception
     */
    public function __set($propName, $value)
    {
        if (strtolower($propName) === 'id') {
            throw new Exception("Property \"{$propName}\" read only in " . static::class
                . " for objectId={$this->objectId}");
        }

        $this->hoClient->trigger(
            "{$this->target}.set.{$propName}.before",
            [$this, &$propName, &$value, &$this->origData]
        );

        $this->changedData[$propName] = $value;

        $this->hoClient->trigger(
            "{$this->target}.set.{$propName}.after",
            [$this, $propName, $value, &$this->origData]
        );
    }

    /**
     * @param string $propName
     *
     * @return bool
     */
    public function __isset($propName)
    {
        $this->reloadIfNeed();

        if (array_key_exists($propName, $this->changedData)) {
            return true;
        }

        return array_key_exists($propName, $this->origData);
    }

    /**
     * @param $propName
     *
     * @throws Exception
     */
    public function __unset($propName)
    {
        $this->reloadIfNeed();

        $this->hoClient->trigger("{$this->target}.unset.{$propName}.before", [$this, &$propName, &$this->origData]);

        if (array_key_exists($propName, $this->origData)) {
            $this->changedData[$propName] = null;
        } else {
            throw new Exception("Undefined property \"{$propName}\" in " . static::class
                . " for objectId={$this->objectId}");
        }

        $this->hoClient->trigger("{$this->target}.unset.{$propName}.after", [$this, $propName, &$this->origData]);
    }
}
