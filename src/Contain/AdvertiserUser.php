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
use Unilead\HasOffers\Entity\Advertiser;
use Unilead\HasOffers\Traits\DataEntity;

/**
 * Class AdvertiserUser
 * @package Unilead\HasOffers
 */
class AdvertiserUser
{
    use DataEntity;

    /**
     * @var Advertiser
     */
    protected $advertiser;

    /**
     * @var Data
     */
    protected $users;

    /**
     * AdvertiserUser constructor.
     *
     * @param array      $data
     * @param Advertiser $advertiser
     */
    public function __construct(array $data, Advertiser $advertiser)
    {
        $this->advertiser = $advertiser;
        $this->users = $data;
    }

    /**
     * @return Data
     */
    public function getRawData()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        ksort($this->users);
        $data = array_reduce($this->users, function ($reduced, $current) {
            $removeKeys = [
                'wants_alerts',
                'SHARED_Users2_id',
                'salt',
                'AFFILIATE_NETWORK_Brands_id',
                '_NETWORK_employees_id',
                'access',
            ];

            foreach ($removeKeys as $removeKey) {
                unset($current[$removeKey]);
            }

            $reduced[] = $current;

            return $reduced;
        });

        return new Data($data);
    }

    /**
     * @inheritdoc
     */
    public function reload()
    {
        $data = $this->getRawData()->getArrayCopy();

        $this->advertiser->getClient()->trigger('advertiser_users.reload.before', [$this, &$data]);

        $this->bindData($data);

        $this->advertiser->getClient()->trigger('advertiser_users.reload.after', [$this, $data]);
    }
}
