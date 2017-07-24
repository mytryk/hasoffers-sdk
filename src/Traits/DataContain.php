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
use JBZoo\Data\Data as JBZooData;

/**
 * Class DataContain
 *
 * @package Unilead\HasOffers\Traits
 */
trait DataContain
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
    public function reload()
    {
        // noop
    }

    /**
     * Check internal state and reload it if need
     */
    public function reloadIfNeed()
    {
        $isEmpty = count($this->origData) === 0;
        if ($isEmpty) {
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
        $this->hoClient->trigger("{$this->target}.bind.before", [$this, &$data, &$this->changedData]);

        foreach (array_keys($data) as $key) {
            if (0 === strpos($key, '_')) {
                unset($data[$key]);
            }
        }

        $this->changedData = (array)$data;

        $this->hoClient->trigger("{$this->target}.bind.after", [$this, &$this->changedData]);

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
     * @param string $propName
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($propName)
    {
        $this->reloadIfNeed();

        if (array_key_exists($propName, $this->changedData)) {
            return $this->changedData[$propName];
        }

        return $this->origData[$propName] ?? null;
    }

    /**
     * @param string $propName
     * @param mixed  $value
     * @throws Exception
     */
    public function __set($propName, $value)
    {
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

        if (array_key_exists($propName, $this->origData)) {
            $this->origData[$propName] = null;
            unset($this->changedData[$propName]);
        }

        $this->hoClient->trigger("{$this->target}.unset.{$propName}.after", [$this, $propName, &$this->origData]);
    }
}
