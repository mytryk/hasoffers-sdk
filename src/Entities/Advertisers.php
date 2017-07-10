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

use Unilead\HasOffers\Entity\Advertiser;
use Unilead\HasOffers\Contain\AdvertiserUser;

/**
 * Class Advertisers
 *
 * @package Unilead\HasOffers\Entities
 */
class Advertisers extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'Advertiser';

    /**
     * @var string
     */
    protected $className = Advertiser::class;

    /**
     * @var array
     */
    protected $contain = [
        'AdvertiserUser' => AdvertiserUser::class
    ];
}
