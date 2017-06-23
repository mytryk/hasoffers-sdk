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

use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Entity\Affiliate;

/**
 * Class Affiliates
 *
 * @package Unilead\HasOffers\Entities
 */
class Affiliates extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'Affiliate';

    /**
     * @var string
     */
    protected $className = Affiliate::class;

    /**
     * @var array
     */
    protected $contain = [
        'PaymentMethod' => PaymentMethod::class,
    ];
}
