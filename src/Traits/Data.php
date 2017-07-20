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

namespace Unilead\HasOffers\Traits;

use Unilead\HasOffers\HasOffersClient;
use JBZoo\Utils\Str;
use JBZoo\Data\Data as JBZooData;

/**
 * Class Entity
 *
 * @package Unilead\HasOffers
 */
trait Data
{
    /** @var  HasOffersClient */
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
        } elseif ($isEmpty && !$isPropExists) { // for contain
            $this->reload();
        }
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function bindData(array $data)
    {
        if (property_exists($this, 'hoClient') && $this->hoClient) {
            $this->hoClient->trigger("{$this->target}.bind.before", [$this, &$data, &$this->changedData]);
        }

        foreach (array_keys($data) as $key) {
            if (0 === strpos($key, '_')) {
                unset($data[$key]);
            }
        }

        $this->changedData = (array)$data;

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
        return new JBZooData(array_merge($this->origData, $this->changedData));
    }

    /**
     * @return array
     */
    public function getChangedFields()
    {
        $this->reloadIfNeed();
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
        if (strpos($method, 'get') !== 0 && strpos($method, 'set') !== 0) {
            throw new Exception('Undefined method ' . static::class . "::{$method}() for objectId={$this->objectId}");
        }

        $propName = Str::splitCamelCase(str_replace(['set', 'get'], '', $method));
        $relatedObjectName = str_replace(['set', 'get'], '', $method);

        if (strpos($method, 'get') === 0) {
            $this->reloadIfNeed();

            if (array_key_exists($relatedObjectName, $this->related)) {
                return $this->related[$relatedObjectName];
            }

            if (!array_key_exists($propName, $this->origData)) {
                throw new Exception("Undefined property \"{$propName}\" or related object \"{$relatedObjectName}\" in "
                    . static::class . " for objectId={$this->objectId}");
            }

            return $this->__get($propName);
        }

        if (strpos($method, 'set') === 0) {
            if (array_key_exists('0', $arguments)) {
                $this->__set($propName, $arguments[0]);
            } else {
                throw new Exception("First argument is required for \"{$propName}\" setter  in " . static::class
                    . " for objectId={$this->objectId}");
            }
        }

        return $this;
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
        $propName = Str::splitCamelCase($propName);

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
        $propName = Str::splitCamelCase($propName);

        return isset($this->origData[$propName]);
    }

    /**
     * @param $propName
     *
     * @throws Exception
     */
    public function __unset($propName)
    {
        $this->hoClient->trigger("{$this->target}.unset.{$propName}.before", [$this, &$propName, &$this->origData]);

        $propName = Str::splitCamelCase($propName);
        if (array_key_exists($propName, $this->origData)) {
            $this->origData[$propName] = null;
            unset($this->changedData[$propName]);
        } else {
            throw new Exception("Undefined property \"{$propName}\" in " . static::class
                . " for objectId={$this->objectId}");
        }

        $this->hoClient->trigger("{$this->target}.unset.{$propName}.after", [$this, $propName, &$this->origData]);
    }
}
