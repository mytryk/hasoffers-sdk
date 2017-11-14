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

namespace Unilead\HasOffers\Entities;

use Unilead\HasOffers\Entity\OfferPixel;

/**
 * Class OfferPixels
 *
 * @package Unilead\HasOffers\Entities
 */
class OfferPixels extends AbstractEntities
{
    const DEFAULT_LIMIT = 100000;

    /**
     * @var string
     */
    protected $target = 'OfferPixel';

    /**
     * @var int
     */
    protected $pageSize = 100000;

    /**
     * @var string
     */
    protected $className = OfferPixel::class;

    /**
     * @var array
     */
    protected $defaultSort = [];

    /**
     * @param array $listResult
     * @return array
     */
    protected function prepareResults($listResult)
    {
        $this->hoClient->trigger("{$this->target}.find.prepare.before", [$this]);

        $result = [];
        foreach ($listResult as $itemId => $itemData) {
            $result[$itemId] = array_values($itemData[$this->target]);
            //$result[$itemId] = $itemData[$this->target]; // For debug indexes
        }

        $this->hoClient->trigger("{$this->target}.find.prepare.after", [$this, &$result]);

        return $result;
    }
}
