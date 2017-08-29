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

use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceItem
 *
 * @property string actions         Integer    The contents of this field are applicable only if "type" is "stats".
 *                                  Count of the number of clicks, conversions, or impressions, for the date covered
 *                                  by the Invoice start and end dates.
 *                                  The type of event contained in this column depends on the "payout_type". For "cpc"
 *                                  payout type this is number of clicks. For "cpm" this is number of impressions. For
 *                                  other payout types this is number of conversions.
 * @property string amount          Decimal    Amount owed for the offer based on invoice start and end dates
 * @property string conversions     Integer    DEPRECATED. The "actions" field should be consulted instead, to
 *                                  determine the number of clicks/conversions/impressions (if "type" is "stats").
 * @property string datetime        Datetime    Date that the invoice item was created
 * @property string goal_id         Nullable Integer    Applicable only if the "enable_offer_goals" Preference is
 *                                  enabled. It specifies the Goal this invoice item is attributed, or NULL if the
 *                                  item is not attributed to a Goal.
 * @property string id              Integer    ID of unique, auto-generated object for this Invoice Item
 * @property string invoice_id      Integer    The ID of the Invoice to which this Invoice Item belongs
 * @property string memo            Nullable String    Memo for this Invoice Item
 * @property string offer_id        Nullable Integer    The ID of the Offer to which this item is attributed. Present
 *                                  when the "type" is "stats".
 * @property string revenue_type    String    The revenue type of the Offer (or the Goal if present) if the "type" is
 *                                  "stats", or the adjustment type if the "type" is "adjustment". For types other than
 *                                  "stats" set to "amount"
 * @property string type            String    Type of invoice item. "stats" indicates this is for
 *                                  clicks/conversions/impressions (depending on "revenue_type"). "adjustment" is for
 *                                  an adjustment. "vat" is for VAT/tax items.
 * @property string vat_code        Nullable String    The VAT code. Only applicable if "type" is "vat".
 * @property string vat_id          Nullable Integer    The ID of this VAT rate. Only applicable if "type" is "vat".
 * @property string vat_name        Nullable String    The name of this VAT rate. Only applicable if "type" is "vat".
 * @property string vat_rate        Nullable Decimal    The VAT rate being used to calculate tax, as a percentage (e.g.
 *                                  15.00). Only applicable if "type" is "vat".
 *
 * @package Unilead\HasOffers
 */
class AdvertiserInvoiceItem extends AbstractContain
{
    /**
     * @var AdvertiserInvoice
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $target = 'AdvertiserInvoiceItem';

    /**
     * @return mixed
     */
    public function delete()
    {
        // TODO: допилить
//        if (!$this->isExists()) {
//            throw new Exception('Try to delete non object!');
//        }

        $this->getClient()->trigger('advertiser-invoice-item.delete.before', [$this, &$this->changedData]);

        $data = $this->hoClient->apiRequest([
            'Method' => $this->methods['delete'],
            'Target' => $this->target,
            'id'     => $this->id,
        ]);

        $this->getClient()->trigger('advertiser-invoice-item.delete.after', [$this, &$this->changedData]);

        return $data;
    }

    public function save()
    {
        // TODO:
        // if !exist
        // create
        // else
        // delete - silent(no event)
        // create
    }

    public function create()
    {
        // check for existing
        // create - save
        // return
    }
}
