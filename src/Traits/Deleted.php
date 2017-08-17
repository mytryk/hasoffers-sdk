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
 * Class DeletedTrait
 *
 * @package Unilead\HasOffers
 */
trait Deleted
{
    /**
     * @return null
     */
    public function delete()
    {
        $this->hoClient->trigger("{$this->target}.delete.before", [$this]);

        $this->status = 'deleted'; // Replace hardcore to const...
        $this->save();

        $this->hoClient->trigger("{$this->target}.delete.after");
    }

    /**
     * @return null
     */
    public function activate()
    {
        $this->hoClient->trigger("{$this->target}.active.before", [$this]);

        $this->status = 'active'; // Replace hardcore to const...
        $this->save();

        $this->hoClient->trigger("{$this->target}.active.after");
    }
}
