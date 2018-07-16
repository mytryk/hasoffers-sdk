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

/**
 * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
 */

namespace PHPSTORM_META {

    use Item8\HasOffers\Entities\Advertisers;
    use Item8\HasOffers\Entities\Affiliates;
    use Item8\HasOffers\Entities\Employees;
    use Item8\HasOffers\Entities\AdvertiserInvoices;
    use Item8\HasOffers\Entities\AdvertiserUsers;
    use Item8\HasOffers\Entities\AffiliateUsers;
    use Item8\HasOffers\Entities\Offers;
    use Item8\HasOffers\Entities\OfferPixels;
    use Item8\HasOffers\Entities\Conversions;

    use Item8\HasOffers\Entity\Advertiser;
    use Item8\HasOffers\Entity\AdvertiserInvoice;
    use Item8\HasOffers\Entity\AdvertiserUser;
    use Item8\HasOffers\Entity\Affiliate;
    use Item8\HasOffers\Entity\AffiliateInvoice;
    use Item8\HasOffers\Entity\AffiliateReceipt;
    use Item8\HasOffers\Entity\AffiliateUser;
    use Item8\HasOffers\Entity\Conversion;
    use Item8\HasOffers\Entity\Employee;
    use Item8\HasOffers\Entity\Offer;
    use Item8\HasOffers\Entity\OfferPixel;

    use Item8\HasOffers\Request\AbstractRequest;

    override(AbstractRequest::get(0),
        map([
            // object
            Advertiser::class         => Advertiser::class,
            AdvertiserInvoice::class  => AdvertiserInvoice::class,
            AdvertiserUser::class     => AdvertiserUser::class,
            Affiliate::class          => Affiliate::class,
            AffiliateInvoice::class   => AffiliateInvoice::class,
            AffiliateReceipt::class   => AffiliateReceipt::class,
            AffiliateUser::class      => AffiliateUser::class,
            Employee::class           => Employee::class,
            Offer::class              => Offer::class,
            Conversion::class         => Conversion::class,
            OfferPixel::class         => OfferPixel::class,
            // list
            Advertisers::class        => Advertisers::class,
            AdvertiserInvoices::class => AdvertiserInvoices::class,
            AdvertiserUsers::class    => AdvertiserUsers::class,
            AffiliateUsers::class     => AffiliateUsers::class,
            Affiliates::class         => Affiliates::class,
            Employees::class          => Employees::class,
            Offers::class             => Offers::class,
            OfferPixels::class        => OfferPixels::class,
            Conversions::class        => Conversions::class,
        ]));
}
