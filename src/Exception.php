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

namespace Unilead\HasOffers;

use JBZoo\Event\EventManager;
use Throwable;

/**
 * Class Exception
 *
 * @package Unilead\HasOffers
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
