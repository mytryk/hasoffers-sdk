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

namespace JBZoo\PHPUnit;

use Unilead\HasOffers\Entities\OfferPixels;

/**
 * Class OfferPixelsTest
 *
 * @package JBZoo\PHPUnit
 */
class OfferPixelsTest extends HasoffersPHPUnit
{
    /**
     * @var OfferPixels
     */
    protected $offerPixels;

    public function setUp()
    {
        parent::setUp();
        $this->offerPixels = $this->hoClient->get(OfferPixels::class);
    }

    public function testFindOneRow()
    {
        $list = $this->offerPixels->find([
            'sort'  => ['id' => 'asc'],
            'limit' => 1,
        ]);

        isSame(0, count($list));

        // TODO: Add OfferPixel to Demo HO (Den)

        isSame(1, $this->hoClient->getRequestCounter());
        isNull($this->hoClient->getLastResponse());
    }
}
