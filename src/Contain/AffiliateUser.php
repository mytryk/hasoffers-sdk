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
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Traits\Data as DataTrait;

/**
 * Class AffiliateUser
 * @package Unilead\HasOffers
 */
class AffiliateUser
{
    use DataTrait;

    /**
     * @var Affiliate
     */
    protected $affiliate;

    /**
     * @var Data
     */
    protected $users;

    /**
     * AffiliateUser constructor.
     *
     * @param array     $data
     * @param Affiliate $affiliate
     */
    public function __construct(array $data, Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
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

        $this->affiliate->getClient()->trigger('affiliate_users.reload.before', [$this, &$data]);

        $this->bindData($data);

        $this->affiliate->getClient()->trigger('affiliate_users.reload.after', [$this, $data]);
    }
}
