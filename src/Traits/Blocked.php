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

namespace Unilead\HasOffers\Traits;

/**
 * Class BlockedTrait
 *
 * @package Unilead\HasOffers
 */
trait Blocked
{
    /**
     * @return null
     */
    public function delete()
    {
        $this->hoClient->trigger("{$this->target}.block.before", [$this]);

        $this->status = 'blocked'; // Replace hardcore to const...
        $this->save();

        $this->hoClient->trigger("{$this->target}.block.after");
    }
}
