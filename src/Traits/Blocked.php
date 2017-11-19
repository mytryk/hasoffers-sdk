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

namespace Item8\HasOffers\Traits;

/**
 * Class BlockedTrait
 *
 * @package Item8\HasOffers
 */
trait Blocked
{
    /**
     * @return null
     */
    public function block()
    {
        $this->hoClient->trigger("{$this->target}.block.before", [$this]);

        $this->status = 'blocked'; // Replace hardcore to const...
        $this->save();

        $this->hoClient->trigger("{$this->target}.block.after");
    }

    /**
     * Unblock Affiliate in HasOffers.
     *
     * @return $this
     */
    public function unblock()
    {
        $this->hoClient->trigger("{$this->target}.unblock.before", [$this]);

        $this->status = 'active';
        $result = $this->save();

        $this->hoClient->trigger("{$this->target}.unblock.after", [$this]);

        return $result;
    }
}
