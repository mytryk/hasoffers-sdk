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

use JBZoo\Utils\FS;
use JBZoo\Utils\Sys;

if (!defined('ROOT_PATH')) { // for PHPUnit process isolation
    define('ROOT_PATH', realpath('.'));
}

// main autoload
if ($autoload = realpath('./vendor/autoload.php')) {
    require_once $autoload;

    FS::rmdir(PROJECT_BUILD . '/dumps');
    mkdir(PROJECT_BUILD . '/dumps', 0777, true);
    Sys::setMemory('512M');

} else {
    echo 'Please execute "composer update" !' . PHP_EOL;
    exit(1);
}
