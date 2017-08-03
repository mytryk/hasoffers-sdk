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

namespace JBZoo\PHPUnit;

use Unilead\HasOffers\Entity\AffiliateUser;
use Unilead\HasOffers\Entities\AffiliateUsers;

/**
 * Class AffiliateUsersTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateUsersTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        /** @var AffiliateUsers $users */
        $users = $this->hoClient->get(AffiliateUsers::class);
        $list = $users->find([
            'limit' => 100,
            'sort'  => [],
        ]);

        /** @var AffiliateUser $user */
        $user = $list[8];

        isSame('Jack', $user->first_name);
        isSame('Birdman', $user->last_name);
        isSame('jackisthebest@birdman.aus', $user->email);
        isSame('Owner', $user->title);
    }
}
