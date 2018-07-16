<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Entities;

use Item8\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateUsers
 *
 * @package Item8\HasOffers\Entities
 */
class AffiliateUsers extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'AffiliateUser';

    /**
     * @var string
     */
    protected $className = AffiliateUser::class;
}
