<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Entities\OfferPixels;

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
