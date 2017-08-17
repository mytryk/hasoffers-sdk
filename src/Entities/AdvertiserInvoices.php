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

use Unilead\HasOffers\Contain\AdvertiserInvoiceItem;
use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoices
 *
 * @package Unilead\HasOffers\Entities
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
