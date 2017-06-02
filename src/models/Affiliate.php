<?php
/**
 * Unilead | BM
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     BM
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace Unilead\HasOffers\Models;

use Unilead\HasOffers\Request;

/**
 * Class Affiliate
 *
 * @package Unilead\HasOffers\Models
 */
class Affiliate
{
    //this
    public function __construct()
    {
        $this->request = new Request();
    }

    //or this
    public function setRequest(Request $request)
    {
        return $this->request = $request;
    }

    public function getAll()
    {
        return false;
    }

    public function get()
    {
        return false;
    }

    public function create()
    {
        return false;
    }

    public function update()
    {
        return false;
    }

    public function delete()
    {
        return false;
    }

    public function block()
    {
        return false;
    }

    public function unblock()
    {
        return false;
    }

    public function updatePaymentMethodWire()
    {
        return false;
    }

    public function updatePaymentMethodPaypal()
    {
        return false;
    }

    public function updatePaymentMethodPayoneer()
    {
        return false;
    }

    public function updatePaymentMethodOther()
    {
        return false;
    }
}
