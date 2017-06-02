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
use Unilead\HasOffers\Models\Affiliate;

class AffiliateTest extends PHPUnit
{
    protected $affiliate;

    public function setUp()
    {
        skip('Write me.');

        parent::setUp();

        //Env::get('API_URL')
        //Env::get('API_NETWORK_ID')
        //Env::get('API_NETWORK_TOKEN')

        $this->affiliate = new Affiliate();
    }

    public function testUserCanGetAffiliate()
    {
        skip('Write me.');

        $this->affiliate->create();
    }

    public function testUserCanCreateAffiliate()
    {
        skip('Write me.');
    }
    
    public function testUserCanUpdateAffiliate()
    {
        skip('Write me.');
    }
    
    public function testUserCanBlockAffiliate()
    {
        skip('Write me.');
    }

    public function testUserCanUnblockAffiliate()
    {
        skip('Write me.');
    }

    public function testUserCanDeleteAffiliate()
    {
        skip('Write me.');
    }

    public function testUserCanUpdatePaymentMethodWire()
    {
        skip('Write me.');
    }

    public function testUserCanUpdatePaymentMethodPayoneer()
    {
        skip('Write me.');
    }

    public function testUserCanUpdatePaymentMethodPaypal()
    {
        skip('Write me.');
    }

    public function testUserCanUpdatePaymentMethodOther()
    {
        skip('Write me.');
    }
}
