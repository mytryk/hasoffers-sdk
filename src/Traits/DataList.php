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

namespace Item8\HasOffers\Traits;

/**
 * Class DataList
 *
 * @package Item8\HasOffers\Traits
 */
trait DataList
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->items;
    }

    /**
     * @param $itemId
     * @return bool
     */
    public function findById($itemId)
    {
        $searchId = (int)$itemId;
        foreach ($this->items as $item) {
            if ($searchId === (int)$item->id) {
                return $item;
            }
        }

        return false;
    }
}
