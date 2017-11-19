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

namespace Item8\HasOffers\Contain;

use Item8\HasOffers\Entity\AbstractEntity;
use Item8\HasOffers\Traits\DataContain;

/**
 * Class AbstractContain
 *
 * @package Item8\HasOffers
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
     * @var string
     */
    protected $triggerTarget = '';

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
