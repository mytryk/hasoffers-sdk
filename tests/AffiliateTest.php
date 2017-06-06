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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Env;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\Models\Affiliate;

class AffiliateTest extends PHPUnit
{
    protected $hasOffersClient;

    public function setUp()
    {
        parent::setUp();

        $this->hasOffersClient = new HasOffersClient(
            Env::get('API_URL'),
            Env::get('API_NETWORK_ID'),
            Env::get('API_NETWORK_TOKEN')
        );
    }

    public function testUserCanCreateAffiliate()
    {
        $affiliate = $this->hasOffersClient->get(Affiliate::class);

        $affiliate->setCompany('Test Company')
            ->setAccountManagerId(1)
            ->setPhone('+7 845 845 84 54')
            ->setEmail('test@test.com')
            ->setStatus(Affiliate::STATUS_ACTIVE)
            ->save();

        $affiliateCheck = $this->hasOffersClient->get(Affiliate::class, $affiliate->id);

        isSame($affiliate->id, $affiliateCheck->id);
        isSame($affiliate->company, $affiliateCheck->company);
    }

    public function testUserCanDeleteAffiliate()
    {
        $affiliate = $this->hasOffersClient->get(Affiliate::class, 2);

        $affiliate->delete();

        isSame('deleted', $affiliate->status);
    }

    public function testUserCanGetAffiliate()
    {
        skip('write me');
//        $affiliate = $this->affiliate->get(1);
//        isSame(5, $affiliate['id']);
    }

    public function testUserCanUpdateAffiliate()
    {
        skip('write me');
//        $data = [
//            'company' => 'Company Test',
//            'account_manager_id' => 2
//        ];
//
//        $affiliate = $this->affiliate->update(2, $data);
//        isSame($data['company'], $affiliate['company']);
//        isSame($data['account_manager_id'], $affiliate['account_manager_id']);
    }

    public function testUserCanBlockAffiliate()
    {
        skip('write me');
//        $HasOffersClient = new HasOffersClient($apiUrl, $networkId, $networkToken);
//        $Affiliate = $HasOffersClient->get(Affiliate::class, 2);
//
//        $Affiliate->block();
//
//        isSame('blocked', $affiliate['status']);
    }

    public function testUserCanUnblockAffiliate()
    {
        skip('write me');
//        $HasOffersClient = new HasOffersClient($apiUrl, $networkId, $networkToken);
//        $Affiliate = $HasOffersClient->get(Affiliate::class, 2);
//
//        $Affiliate->unblock();
//
//        isSame('active', $affiliate['status']);
    }
}
