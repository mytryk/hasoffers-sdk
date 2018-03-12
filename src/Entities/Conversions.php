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

namespace Item8\HasOffers\Entities;

use Item8\HasOffers\Entity\Conversion;

/**
 * Class Convertions
 *
 * @package Item8\HasOffers\Entities
 */
class Conversions extends AbstractEntities
{
    const DEFAULT_LIMIT = 100000;

    /**
     * @var string
     */
    protected $target = 'Conversion';

    /**
     * @var int
     */
    protected $pageSize = 100000;

    /**
     * @var string
     */
    protected $className = Conversion::class;

    /**
     * @var array
     */
    protected $defaultSort = [];

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        //parent::__construct();
        $this->defaultFields = Conversion::$fields;
    }

    /**
     * @param array $listResult
     * @return array
     */
    protected function prepareResults($listResult)
    {
        $this->hoClient->trigger("{$this->target}.find.prepare.before", [$this]);

        $result = [];
        foreach ($listResult as $itemId => $itemData) {
            $result[$itemId] = array_values($itemData[$this->target]);
            //$result[$itemId] = $itemData[$this->target]; // For debug indexes
        }

        $this->hoClient->trigger("{$this->target}.find.prepare.after", [$this, &$result]);

        return $result;
    }
}
