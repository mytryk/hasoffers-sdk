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

namespace Item8\HasOffers\Entities;

use Item8\HasOffers\Contain\AffiliateMeta;
use Item8\HasOffers\Entity\Affiliate;
use Item8\HasOffers\Contain\PaymentMethod;
use Item8\HasOffers\Contain\AffiliateUser;

/**
 * Class Affiliates
 *
 * @package Item8\HasOffers\Entities
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
        'AffiliateUser' => AffiliateUser::class,
        'AffiliateMeta' => AffiliateMeta::class,
    ];
}
