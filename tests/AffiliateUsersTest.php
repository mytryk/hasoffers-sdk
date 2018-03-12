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

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Entity\AffiliateUser;
use Item8\HasOffers\Entities\AffiliateUsers;

/**
 * Class AffiliateUsersTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateUsersTest extends HasoffersPHPUnit
{
    protected $testId = '8';

    public function testFindList()
    {
        /** @var AffiliateUsers $users */
        $users = $this->hoClient->get(AffiliateUsers::class);
        $list = $users->find([
            'filters' => [
                'id' => $this->testId,
            ],
        ]);

        /** @var AffiliateUser $user */
        $user = $list[$this->testId];

        isSame('Jack', $user->first_name);
        isSame('Birdman', $user->last_name);
        isSame('jackisthebest@birdman.aus', $user->email);
        isSame('Owner', $user->title);
    }
}
