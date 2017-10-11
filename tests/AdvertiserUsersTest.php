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

use Unilead\HasOffers\Entity\AdvertiserUser;
use Unilead\HasOffers\Entities\AdvertiserUsers;

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
