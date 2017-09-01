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

namespace Unilead\HasOffers\Contain;

use Unilead\HasOffers\Entity\AbstractEntity;
use Unilead\HasOffers\Traits\DataContain;

/**
 * Class AbstractContain
 *
 * @package Unilead\HasOffers
 */
abstract class AbstractContain
{
    use DataContain;

    /**
     * @var AbstractEntity
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $target = '';

    /**
     * @var array
     */
    protected $excludedKeys = [];

    /**
     * AbstractContain constructor.
     *
     * @param array          $data
     * @param AbstractEntity $parentEntity
     * @throws Exception
     */
    public function __construct(array $data, AbstractEntity $parentEntity)
    {
        $this->parentEntity = $parentEntity;
        $this->hoClient = $this->parentEntity->getClient();
        $this->bindData($data);
        $this->origData = $data;

        if (!$this->target) {
            throw new Exception('Target is no set for ' . static::class);
        }
    }

    /**
     * @return AbstractEntity
     */
    public function getParent()
    {
        return $this->parentEntity;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function removeExcludedKeys($data)
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
}
