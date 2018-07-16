<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Traits;

/**
 * Class DeletedTrait
 *
 * @package Item8\HasOffers
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
