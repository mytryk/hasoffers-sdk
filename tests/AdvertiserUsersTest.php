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

use Item8\HasOffers\Entity\AdvertiserUser;
use Item8\HasOffers\Entities\AdvertiserUsers;

/**
 * Class AdvertiserUsersTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserUsersTest extends HasoffersPHPUnit
{
    protected $testId = '4';

    public function testFindList()
    {
        /** @var AdvertiserUsers $users */
        $users = $this->hoClient->get(AdvertiserUsers::class);
        $list = $users->find();

        /** @var AdvertiserUser $user */
        $user = $list[$this->testId];

        isNotEmpty($user->first_name);
    }
}
