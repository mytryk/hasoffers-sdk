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

namespace Unilead\HasOffers\Contain;

use JBZoo\Data\Data;
use Unilead\HasOffers\Traits\Data as DataTrait;
use Unilead\HasOffers\Entity\Affiliate;

/**
 * Class PaymentMethod
 * @package Unilead\HasOffers
 */
class PaymentMethod
{
    use DataTrait;

    const TYPE_CHECK         = 'Check';
    const TYPE_DIRECTDEPOSIT = 'DirectDeposit';
    const TYPE_OTHER         = 'Other';
    const TYPE_PAYONEER      = 'Payoneer';
    const TYPE_PAYPAL        = 'Paypal';
    const TYPE_PAYQUICKER    = 'PayQuicker';
    const TYPE_WIRE          = 'Wire';

    /**
     * @var Affiliate
     */
    protected $affiliate;

    /**
     * @var Data
     */
    protected $paymentData;

    /**
     * PaymentMethod constructor.
     * @param array     $data
     * @param Affiliate $affiliate
     */
    public function __construct(array $data, Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
        $this->paymentData = new Data($data);
    }

    /**
     * @return string
     */
    public function getType()
    {
        if ($this->paymentData->find(self::TYPE_CHECK . '.payable_to')) {
            return self::TYPE_CHECK;
        }

        if ($this->paymentData->find(self::TYPE_DIRECTDEPOSIT . '.account_holder')) {
            return self::TYPE_DIRECTDEPOSIT;
        }

        if ($this->paymentData->find(self::TYPE_PAYONEER . '.status')) {
            return self::TYPE_PAYONEER;
        }

        if ($this->paymentData->find(self::TYPE_PAYPAL . '.email')) {
            return self::TYPE_PAYPAL;
        }

        if ($this->paymentData->find(self::TYPE_PAYQUICKER . '.m2m_email')) {
            return self::TYPE_PAYQUICKER;
        }

        if ($this->paymentData->find(self::TYPE_WIRE . '.beneficiary_name')) {
            return self::TYPE_WIRE;
        }

        return self::TYPE_OTHER;
    }

    /**
     * @return Data
     */
    public function getRawData()
    {
        return $this->paymentData->get($this->getType(), [], 'data');
    }

    /**
     * @inheritdoc
     */
    public function reload()
    {
        $this->bindData($this->getRawData()->getArrayCopy());
    }
}
