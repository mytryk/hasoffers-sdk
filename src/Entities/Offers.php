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

use Unilead\HasOffers\Contain\Goal;
use Unilead\HasOffers\Entity\Offer;

/**
 * Class Offers
 *
 * @package Unilead\HasOffers\Entities
 */
class Offers extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'Offer';

    /**
     * @var string
     */
    protected $className = Offer::class;

    /**
     * @var array
     */
    protected $contain = [
        'Goal' => Goal::class,
    ];
}
