<?php
/**
 * Unilead | BM
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     BM
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

/**
 * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
 */

namespace PHPSTORM_META {

    use \Unilead\HasOffers\HasOffersClient;
    use \Unilead\HasOffers\Entity\Advertiser;
    use \Unilead\HasOffers\Entity\AdvertiserInvoice;
    use \Unilead\HasOffers\Entity\AdvertiserInvoiceItem;
    use \Unilead\HasOffers\Entity\AdvertiserUser;
    use \Unilead\HasOffers\Entity\Affiliate;
    use \Unilead\HasOffers\Entity\AffiliateInvoice;
    use \Unilead\HasOffers\Entity\AffiliateInvoiceItem;
    use \Unilead\HasOffers\Entity\AffiliateUser;
    use \Unilead\HasOffers\Entity\Employee;
    use \Unilead\HasOffers\Offer;

    override(HasOffersClient::get(0),
        map([
            Advertiser::class            => Advertiser::class,
            AdvertiserInvoice::class     => AdvertiserInvoice::class,
            AdvertiserInvoiceItem::class => AdvertiserInvoiceItem::class,
            AdvertiserUser::class        => AdvertiserUser::class,
            Affiliate::class             => Affiliate::class,
            AffiliateInvoice::class      => AffiliateInvoice::class,
            AffiliateInvoiceItem::class  => AffiliateInvoiceItem::class,
            AffiliateUser::class         => AffiliateUser::class,
            Employee::class              => Employee::class,
            Offer::class                 => Offer::class,
        ]));
}
