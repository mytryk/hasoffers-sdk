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

use JBZoo\Data\Data;
use JBZoo\Utils\FS;

if (!defined('ROOT_PATH')) { // for PHPUnit process isolation
    define('ROOT_PATH', realpath('.'));
}

/**
 * @param array|null|string $data
 * @return Data
 */
function json($data = null)
{
    return JBZoo\Data\json($data);
}

// main autoload
if ($autoload = realpath('./vendor/autoload.php')) {
    require_once $autoload;

    FS::rmdir(PROJECT_BUILD . '/dumps');
    mkdir(PROJECT_BUILD . '/dumps', 0777, true);
} else {
    echo 'Please execute "composer update" !' . PHP_EOL;
    exit(1);
}
