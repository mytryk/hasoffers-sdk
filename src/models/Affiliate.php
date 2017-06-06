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

use Unilead\HasOffers\HasOffersClient;

/**
 * Class Affiliate
 *
 * @package Unilead\HasOffers\Models
 */
class Affiliate
{
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';
    const STATUS_REJECTED = 'rejected';

    private $request;

    private $id;
    private $account_manager_id;
    private $address1;
    private $address2;
    private $city;
    private $company;
    private $country;
    private $date_added;
    private $modified;
    private $payment_method;
    private $payment_terms;
    private $phone;
    private $ref_id;
    private $referral_id;
    private $region;
    private $status;
    private $zipcode;

    private $data = [];

    public function __construct($id = null)
    {
        if ($id !== null && is_int($id)) {
            return $this->get($id);
        }
    }

    public function __call($method, array $arg = [])
    {
        // setName
        $prop = strtolower(str_replace('set', '', $method));
        $this->data[$prop] = $arg[0];

        return $this;
    }

    public function setRequest(HasOffersClient $request)
    {
        return $this->request = $request;
    }

    public function get($id)
    {
        // request to HasOffers
        // fill object with fields



        return false;
    }

    public function save()
    {
        return $this;
    }

    private function create()
    {
        return false;
    }

    private function update()
    {
        return false;
    }

    private function getAll()
    {
        return false;
    }

    private function delete()
    {
        return false;
    }

    private function block()
    {
        return false;
    }

    private function unblock()
    {
        return false;
    }

    private function updatePaymentMethod()
    {
        return false;
    }

    private function updatePaymentMethodWire()
    {
        return false;
    }

    private function updatePaymentMethodPaypal()
    {
        return false;
    }

    private function updatePaymentMethodPayoneer()
    {
        return false;
    }

    private function updatePaymentMethodOther()
    {
        return false;
    }
}
