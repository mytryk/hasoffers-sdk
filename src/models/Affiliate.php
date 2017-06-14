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

use JBZoo\Utils\Str;
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

    private $hasOffersClient;

    public $data = [];

    public function __call($method, array $arg = [])
    {
        $prop = Str::splitCamelCase((str_replace('set', '', $method)));
        $this->data[$prop] = $arg[0];

        return $this;
    }

    /**
     * Setter for HasOffers Client.
     *
     * @param HasOffersClient $hasOffersClient
     *
     * @return $this
     */
    public function setHasOffersClient(HasOffersClient $hasOffersClient)
    {
        $this->hasOffersClient = $hasOffersClient;

        return $this;
    }

    /**
     * Get Affiliate from HasOffers.
     *
     * @param int $affiliateId
     *
     * @return $this
     */
    public function get($affiliateId)
    {
        if (null === $affiliateId) {
            return $this;
        }

        $data = $this->hasOffersClient->apiRequest([
            'Target' => 'Affiliate',
            'Method' => 'findById',
            'id'     => $affiliateId
        ]);

        $this->data = $data['Affiliate'];

        return $this;
    }

    /**
     * Upsert affiliate to HasOffers.
     *
     * @return $this
     */
    public function save()
    {
        // if id is null
        return $this;
    }

    /**
     * Create new Affiliate in HasOffers.
     *
     * @return bool
     */
    private function create()
    {
        return false;
    }

    /**
     * Update affiliate in HasOffers.
     *
     * @param int $affiliateId
     *
     * @return bool
     */
    private function update($affiliateId)
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
