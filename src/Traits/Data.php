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

use JBZoo\Utils\Str;
use JBZoo\Data\Data as JBZooData;

/**
 * Class Entity
 * @package Unilead\HasOffers
 */
trait Data
{
    /**
     * @var array
     */
    public $data;

    /**
     * @return $this
     */
    abstract public function reload();

    /**
     * @param array $data
     *
     * @return $this
     */
    public function bindData(array $data)
    {
        foreach ($data as $key => $item) {
            if (substr($key, 0, strlen('_')) === '_') {
                unset($data[$key]);
            }
        }

        $this->data = (array)$data;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->data = array_merge($this->data, (array)$data);

        return $this;
    }

    /**
     * @return JBZooData
     */
    public function data()
    {
        return new JBZooData($this->data);
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
            throw new Exception('Undefined method ' . static::class . "::{$method}()");
        }

        $propName = Str::splitCamelCase(str_replace(['set', 'get'], '', $method));
        $relatedObjectName = str_replace(['set', 'get'], '', $method);

        if (strpos($method, 'get') === 0) {
            if (!$this->data) {
                $this->reload();
            }

            if (array_key_exists($relatedObjectName, $this->related)) {
                return $this->related[$relatedObjectName];
            }

            if (!array_key_exists($propName, $this->data)) {
                throw new Exception("Undefined property \"{$propName}\" or related object \"{$relatedObjectName}\" in "
                    . static::class);
            }

            return $this->__get($propName);
        }

        if (strpos($method, 'set') === 0) {
            if (array_key_exists('0', $arguments)) {
                $this->hoClient->trigger("{$this->target}.set.before", [&$propName, &$arguments[0], $this->data]);
                $this->__set($propName, $arguments[0]);
                $this->hoClient->trigger("{$this->target}.set.after", [&$propName, &$arguments[0], $this->data]);
            } else {
                throw new Exception("First argument is required for \"{$propName}\" setter  in " . static::class);
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
        if (!$this->data) {
            $this->reload();
        }

        $propName = Str::splitCamelCase($propName);
        if (!array_key_exists($propName, $this->data)) {
            throw new Exception("Undefined property \"{$propName}\" in " . static::class);
        }

        return $this->data[$propName];
    }

    /**
     * @param string $propName
     * @param mixed  $value
     * @throws Exception
     */
    public function __set($propName, $value)
    {
        if (strtolower($propName) === 'id') {
            throw new Exception("Property \"{$propName}\" read only in " . static::class);
        }

        $this->hoClient->trigger("{$this->target}.set.before", [&$propName, &$value, $this->data]);

        $propName = Str::splitCamelCase($propName);
        $this->data[$propName] = $value;

        $this->hoClient->trigger("{$this->target}.set.after", [$propName, $value, $this->data]);
    }

    /**
     * @param string $propName
     *
     * @return bool
     */
    public function __isset($propName)
    {
        $propName = Str::splitCamelCase($propName);

        return isset($this->data[$propName]);
    }

    /**
     * @param $propName
     *
     * @throws Exception
     */
    public function __unset($propName)
    {
        $this->hoClient->trigger("{$this->target}.unset.before", [&$propName, $this->data]);

        $propName = Str::splitCamelCase($propName);
        if (array_key_exists($propName, $this->data)) {
            $this->data[$propName] = null;
        } else {
            throw new Exception("Undefined property \"{$propName}\" in " . static::class);
        }

        $this->hoClient->trigger("{$this->target}.unset.after", [&$propName, $this->data]);
    }
}
