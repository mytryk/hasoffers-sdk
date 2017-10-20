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
use Unilead\HasOffers\Entity\AbstractEntity;
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
class PaymentMethod extends AbstractContain
{
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
     * @var Affiliate
     */
    protected $parentEntity;

    /**
     * @inheritdoc
     */
    public function __construct(array $data, AbstractEntity $parentEntity)
    {
        parent::__construct($data, $parentEntity);

        $data = (new Data($data))->find($this->getType(), [], 'arr');
        $this->bindData($data);
        $this->origData = $data;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getType()
    {
        return ucfirst(strtolower($this->parentEntity->payment_method));
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

        $this->parentEntity->payment_method = strtolower($newPaymentMethod);
        $this->parentEntity->save();
        $this->reload();
    }

    /**
     * Save changed payment method info
     *
     * @param array $properties
     * @return bool
     * @throws \Unilead\HasOffers\Exception
     */
    public function save(array $properties = [])
    {
        if (count($properties) !== 0) {
            return $this->mergeData($properties)->save();
        }

        $changedData = $this->data()->getArrayCopy();

        $this->hoClient->trigger("{$this->target}.save.before", [$this, &$changedData]);

        $result = $this->hoClient->apiRequest([
            'Target'       => $this->parentEntity->getTarget(),
            'Method'       => 'updatePaymentMethod' . $this->getType(),
            'affiliate_id' => $this->parentEntity->id,
            'data'         => $changedData,
        ]);

        if ($result->get('0', null, 'bool')) {
            $newData = array_merge($this->data()->getArrayCopy(), $changedData);
            $this->bindData($newData);
            $this->origData = $newData;
            $this->changedData = [];

            $this->hoClient->trigger("{$this->target}.save.after", [$this, $newData]);

            return true;
        }

        return false;
    }
}
