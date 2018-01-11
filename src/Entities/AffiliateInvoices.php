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

use Item8\HasOffers\Contain\AffiliateInvoiceItem;
use Item8\HasOffers\Entity\AffiliateInvoice;

/**
 * Class AffiliateInvoices
 *
 * @package Item8\HasOffers\Entities
 */
class AffiliateInvoices extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'AffiliateBilling';

    /**
     * @var string
     */
    protected $targetAlias = 'AffiliateInvoice';

    /**
     * @var string
     */
    protected $className = AffiliateInvoice::class;

    /**
     * @var int
     */
    protected $pageSize = 1000;

    /**
     * @var array
     */
    protected $contain = [
        'AffiliateInvoiceItem' => AffiliateInvoiceItem::class,
    ];

    /**
     * @var array
     */
    protected $methods = [
        'findAll' => 'findAllInvoices',
    ];
}
