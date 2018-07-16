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

namespace Item8\HasOffers\Request;

use JBZoo\Event\EventManager;
use Throwable;

/**
 * Class Exception
 *
 * @package Item8\HasOffers
 */
class Exception extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (class_exists(EventManager::class) && EventManager::getDefault()) {
            EventManager::getDefault()->trigger('ho.exception', [$this]);
        }
    }
}
