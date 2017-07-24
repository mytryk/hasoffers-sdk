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
 *
 * @property string affiliate_id
 *
 * @property string account_holder                          DirectDeposit
 * @property string account_number                          DirectDeposit|Wire
 * @property string bank_name                               DirectDeposit|Wire
 * @property string other_details                           DirectDeposit|Wire
 * @property string routing_number                          DirectDeposit|Wire
 *
 * @property string beneficiary_name                        Wire
 *
 * @property string email                                   Paypal
 * @property string modified                                Paypal|Wire
 *
 * @property string status                                  Payoneer
 *
 * @property string details                                 Other
 *
 * @property string advanced_accounting_id                  PayQuicker
 * @property string advanced_email                          PayQuicker
 * @property string advanced_security_id                    PayQuicker
 * @property string advanced_security_id_hint               PayQuicker
 * @property string m2eft_account_name                      PayQuicker
 * @property string m2eft_account_number                    PayQuicker
 * @property string m2eft_account_tax_number                PayQuicker
 * @property string m2eft_account_type                      PayQuicker
 * @property string m2eft_account_type_code                 PayQuicker
 * @property string m2eft_bank_address                      PayQuicker
 * @property string m2eft_bank_name                         PayQuicker
 * @property string m2eft_bank_number                       PayQuicker
 * @property string m2eft_bic                               PayQuicker
 * @property string m2eft_city                              PayQuicker
 * @property string m2eft_description                       PayQuicker
 * @property string m2eft_destination_country_code          PayQuicker
 * @property string m2eft_iban                              PayQuicker
 * @property string m2eft_postal_code                       PayQuicker
 * @property string m2eft_routing_number                    PayQuicker
 * @property string m2m_accounting_id                       PayQuicker
 * @property string m2m_security_id                         PayQuicker
 * @property string m2m_security_id_hint                    PayQuicker
 * @property string m2papercheck_address1                   PayQuicker
 * @property string m2papercheck_address2                   PayQuicker
 * @property string m2papercheck_address3                   PayQuicker
 * @property string m2papercheck_address4                   PayQuicker
 * @property string m2papercheck_check_memo                 PayQuicker
 * @property string m2papercheck_city                       PayQuicker
 * @property string m2papercheck_destination_country_code   PayQuicker
 * @property string m2papercheck_postal_code                PayQuicker
 * @property string m2papercheck_recipient_name             PayQuicker
 * @property string m2papercheck_region                     PayQuicker
 * @property string m2papercheck_return_address1            PayQuicker
 * @property string m2papercheck_return_address2            PayQuicker
 * @property string m2papercheck_return_address3            PayQuicker
 * @property string m2papercheck_return_city                PayQuicker
 * @property string m2papercheck_return_country_code        PayQuicker
 * @property string m2papercheck_return_postal_code         PayQuicker
 * @property string m2papercheck_return_region              PayQuicker
 * @property string method                                  PayQuicker
 * @property string usach_account_number                    PayQuicker
 * @property string usach_account_type                      PayQuicker
 * @property string usach_first_name                        PayQuicker
 * @property string usach_last_name                         PayQuicker
 * @property string usach_routing_number                    PayQuicker
 *
 * @property string payability_affiliate_status             Payability
 * @property string payability_deferred_payment_method      Payability
 * @property string payability_network_status               Payability
 *
 * @property string address1                                Check
 * @property string address2                                Check
 * @property string city                                    Check
 * @property string country                                 Check
 * @property string payable_to                              Check
 * @property string region                                  Check
 * @property string zipcode                                 Check
 * @property string is_individual                           Check
 *
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
    const TYPE_PAYABILITY    = 'Payability';
    const TYPE_UNDEFINED     = 'Undefined';

    /** @var string */
    protected $target = 'PaymentMethod';

    /**
     * @var int
     */
    protected $objectId = -1;

    /**
     * @var Affiliate
     */
    protected $affiliate;

    /**
     * @var Data
     */
    protected $paymentData;

    /**
     * @var string
     */
    protected $forcePaymentMethod;

    /**
     * PaymentMethod constructor.
     *
     * @param array     $data
     * @param Affiliate $affiliate
     */
    public function __construct(array $data, Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;

        $this->paymentData = new Data($data);
        $this->hoClient = $this->affiliate->getClient();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getType()
    {
        return ucfirst(strtolower($this->affiliate->payment_method));
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
     * @return bool
     */
    public function reload()
    {
        $data = $this->getRawData()->getArrayCopy();

        $this->hoClient->trigger("{$this->target}.reload.before", [$this, &$data]);

        $this->bindData($data);
        $this->origData = $data;

        $this->hoClient->trigger("{$this->target}.reload.after", [$this, $data]);
    }

    /**
     * @param string $newPaymentMethod
     * @throws Exception
     */
    public function setType($newPaymentMethod)
    {
        $validList = [
            self::TYPE_CHECK,
            self::TYPE_DIRECTDEPOSIT,
            self::TYPE_OTHER,
            self::TYPE_PAYONEER,
            self::TYPE_PAYPAL,
            self::TYPE_PAYQUICKER,
            self::TYPE_WIRE,
            self::TYPE_PAYABILITY,
        ];

        if (!in_array($newPaymentMethod, $validList, true)) {
            throw new Exception("Undefined new payment method type: {$newPaymentMethod}");
        }

        $this->affiliate->payment_method = strtolower($newPaymentMethod);
        $this->affiliate->save();
        $this->reload();
    }

    /**
     * Save changed payment method info
     */
    public function save()
    {
        $changedData = $this->getChangedFields();
        $this->hoClient->trigger("{$this->target}.save.before", [$this, &$changedData]);

        if (empty($changedData)) {
            return false;
        }

        $result = $this->hoClient->apiRequest([
            'Target'       => $this->affiliate->getTarget(),
            'Method'       => 'updatePaymentMethod' . $this->getType(),
            'affiliate_id' => $this->affiliate->id,
            'data'         => $changedData,
        ]);

        if ($result->get('0', null, 'bool')) {
            $newData = array_merge($this->getRawData()->getArrayCopy(), $changedData);
            $this->bindData($newData);
            $this->origData = $newData;
            $this->changedData = [];

            $this->hoClient->trigger("{$this->target}.save.after", [$this, $newData]);

            return true;
        }

        return false;
    }
}
