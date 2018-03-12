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

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Entities\AffiliateInvoices;
use Item8\HasOffers\Entity\AffiliateInvoice;
use Item8\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateInvoicesTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoicesTest extends HasoffersPHPUnit
{
    protected $testId      = 4;
    protected $affiliateId = 2;

    public function testFindList()
    {
        /** @var AffiliateInvoices $users */
        $invoices = $this->hoClient->get(AffiliateInvoices::class);
        /** @var AffiliateUser $user */
        $condition = [
            'filters' => [
                'id' => $this->testId,
            ],
        ];

        /** @var AffiliateInvoice $invoice */
        $invoice = $invoices->find($condition)[$this->testId];

        isSame($this->testId, (int)$invoice->id);
        isSame(2, (int)$invoice->affiliate_id);
    }
}
