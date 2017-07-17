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

/**
 * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
 */

namespace PHPSTORM_META {

    use Unilead\HasOffers\HasOffersClient;
    use Unilead\HasOffers\Entity\Advertiser;
    use Unilead\HasOffers\Entity\AdvertiserInvoice;
    use Unilead\HasOffers\Entity\AdvertiserInvoiceItem;
    use Unilead\HasOffers\Entity\AdvertiserUser;
    use Unilead\HasOffers\Entity\Affiliate;
    use Unilead\HasOffers\Entity\AffiliateInvoice;
    use Unilead\HasOffers\Entity\AffiliateInvoiceItem;
    use Unilead\HasOffers\Entity\AffiliateUser;
    use Unilead\HasOffers\Entity\Employee;
    use Unilead\HasOffers\Entity\Offer;

    override(HasOffersClient::get(0),
        map([
            Advertiser::class                      => Advertiser::class,
            'Advertiser'                           => Advertiser::class,
            '\Unilead\HasOffers\Entity\Advertiser' => Advertiser::class,

            AdvertiserInvoice::class                      => AdvertiserInvoice::class,
            'AdvertiserInvoice'                           => AdvertiserInvoice::class,
            '\Unilead\HasOffers\Entity\AdvertiserInvoice' => AdvertiserInvoice::class,

            AdvertiserInvoiceItem::class                      => AdvertiserInvoiceItem::class,
            'AdvertiserInvoiceItem'                           => AdvertiserInvoiceItem::class,
            '\Unilead\HasOffers\Entity\AdvertiserInvoiceItem' => AdvertiserInvoiceItem::class,

            AdvertiserUser::class                      => AdvertiserUser::class,
            'AdvertiserUser'                           => AdvertiserUser::class,
            '\Unilead\HasOffers\Entity\AdvertiserUser' => AdvertiserUser::class,

            Affiliate::class                      => Affiliate::class,
            'Affiliate'                           => Affiliate::class,
            '\Unilead\HasOffers\Entity\Affiliate' => Affiliate::class,

            AffiliateInvoice::class                      => AffiliateInvoice::class,
            'AffiliateInvoice'                           => AffiliateInvoice::class,
            '\Unilead\HasOffers\Entity\AffiliateInvoice' => AffiliateInvoice::class,

            AffiliateInvoiceItem::class                      => AffiliateInvoiceItem::class,
            'AffiliateInvoiceItem'                           => AffiliateInvoiceItem::class,
            '\Unilead\HasOffers\Entity\AffiliateInvoiceItem' => AffiliateInvoiceItem::class,

            AffiliateUser::class                      => AffiliateUser::class,
            'AffiliateUser'                           => AffiliateUser::class,
            '\Unilead\HasOffers\Entity\AffiliateUser' => AffiliateUser::class,

            Employee::class                      => Employee::class,
            'Employee'                           => Employee::class,
            '\Unilead\HasOffers\Entity\Employee' => Employee::class,

            Offer::class                      => Offer::class,
            'Offer'                           => Offer::class,
            '\Unilead\HasOffers\Entity\Offer' => Offer::class,
        ]));
}
