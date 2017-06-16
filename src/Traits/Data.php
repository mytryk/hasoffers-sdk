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
use Unilead\HasOffers\Exception;

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
     * @return $this
     */
    public function bindData(array $data)
    {
        $this->data = (array)$data;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->data = array_merge($this->data, (array)$data);
        return $this;
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string $method
     * @param array  $argements
     * @return $this|string
     * @throws Exception
     */
    public function __call($method, array $argements = [])
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

            return $this->data[$propName];
        }

        if (strpos($method, 'set') === 0) {
            if (array_key_exists('0', $argements)) {
                $this->data[$propName] = $argements[0];
            } else {
                throw new Exception("First argement is required for \"{$propName}\" setter  in " . static::class);
            }
        }

        return $this;
    }

    /**
     * @param string $propName
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
     */
    public function __set($propName, $value)
    {
        $propName = Str::splitCamelCase($propName);
        $this->data[$propName] = $value;
    }

    /**
     * @param string $propName
     * @return bool
     */
    public function __isset($propName)
    {
        $propName = Str::splitCamelCase($propName);
        return isset($this->data[$propName]);
    }

    /**
     * @param $propName
     */
    public function __unset($propName)
    {
        $propName = Str::splitCamelCase($propName);
        $this->data[$propName] = null;
    }
}
