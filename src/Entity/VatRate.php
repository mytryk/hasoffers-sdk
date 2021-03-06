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

namespace Item8\HasOffers\Entity;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class VatRate
 *
 * @property string    $id                  A unique, auto-generated ID for the VatRate
 * @property string    $code                The code for this VAT Rate
 * @property \Datetime $created             The date this VAT Rate was created
 * @property \Datetime $modified            The last time this VAT Rate was modified
 * @property string    $name                The name given to this VAT Rate
 * @property float     $rate                The percentage of the VAT
 *
 * @package Item8\HasOffers\Entity
 */
class VatRate extends AbstractEntity
{
    /**
     * @var string
     */
    protected $target = 'VatRate';

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findById',
        'create' => 'create',
        'update' => 'update',
        'delete' => 'delete',
    ];

    /**
     * @return mixed
     * @throws \Item8\HasOffers\Exception
     */
    public function delete()
    {
        $this->hoClient->trigger("{$this->target}.delete.before", [$this, &$this->changedData]);

        $data = $this->hoClient->apiRequest([
            'Method' => $this->methods['delete'],
            'Target' => $this->target,
            'id'     => $this->id,
        ]);

        $this->hoClient->trigger("{$this->target}.delete.after", [$this, &$this->changedData]);

        return $data;
    }
}
