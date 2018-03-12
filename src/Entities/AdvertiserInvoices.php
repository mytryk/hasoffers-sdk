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

use Item8\HasOffers\Contain\AdvertiserInvoiceItem;
use Item8\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoices
 *
 * @package Item8\HasOffers\Entities
 */
class AdvertiserInvoices extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'AdvertiserBilling';

    /**
     * @var string
     */
    protected $targetAlias = 'AdvertiserInvoice';

    /**
     * @var string
     */
    protected $className = AdvertiserInvoice::class;

    /**
     * @var array
     */
    protected $contain = [
        'AdvertiserInvoiceItem' => AdvertiserInvoiceItem::class,
    ];

    /**
     * @var array
     */
    protected $methods = [
        'findAll' => 'findAllInvoices',
    ];
}
