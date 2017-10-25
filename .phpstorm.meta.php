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

    use Unilead\HasOffers\Entities\Advertisers;
    use Unilead\HasOffers\Entities\Affiliates;
    use Unilead\HasOffers\Entities\Employees;
    use Unilead\HasOffers\Entities\AdvertiserInvoices;
    use Unilead\HasOffers\Entities\AdvertiserUsers;
    use Unilead\HasOffers\Entities\AffiliateUsers;
    use Unilead\HasOffers\Entities\Offers;
    use Unilead\HasOffers\Entities\Conversions;

    use Unilead\HasOffers\Entity\Advertiser;
    use Unilead\HasOffers\Entity\AdvertiserInvoice;
    use Unilead\HasOffers\Entity\AdvertiserUser;
    use Unilead\HasOffers\Entity\Affiliate;
    use Unilead\HasOffers\Entity\AffiliateInvoice;
    use Unilead\HasOffers\Entity\AffiliateReceipt;
    use Unilead\HasOffers\Entity\AffiliateUser;
    use Unilead\HasOffers\Entity\Employee;
    use Unilead\HasOffers\Entity\Offer;

    use Unilead\HasOffers\HasOffersClient;

    override(HasOffersClient::get(0),
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
            // list
            Advertisers::class        => Advertisers::class,
            AdvertiserInvoices::class => AdvertiserInvoices::class,
            AdvertiserUsers::class    => AdvertiserUsers::class,
            AffiliateUsers::class     => AffiliateUsers::class,
            Affiliates::class         => Affiliates::class,
            Employees::class          => Employees::class,
            Offers::class             => Offers::class,
            Conversions::class        => Conversions::class,
        ]));
}
