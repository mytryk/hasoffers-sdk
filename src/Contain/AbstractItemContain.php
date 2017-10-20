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

use Unilead\HasOffers\Entity\AbstractEntity;
use Unilead\HasOffers\Traits\DataContain;

/**
 * Class AbstractContain
 *
 * @package Unilead\HasOffers
 */
abstract class AbstractItemContain extends AbstractContain
{
    /**
     * @param array $properties
     * @return $this
     * @throws Exception
     */
    public function save(array $properties = [])
    {
        if (count($properties) !== 0) {
            return $this->mergeData($properties)->save();
        }

        $isNew = !$this->id;
        $this->hoClient->trigger($this->triggerTarget . '.create.before', [$this, &$this->changedData]);

        if ($isNew) {
            if (count($this->changedData) === 0) {
                throw new Exception('No data to create new object "' . static::class . '" in HasOffers');
            }
        } else {
            $dataRequest = $this->getChangedFields();
            if (count($dataRequest) === 0) {
                throw new Exception('No data to update object "' . static::class . '" in HasOffers');
            }

            $this->remove();
        }

        $this->mergeData($this->getChangedFields());
        $dataForCreate = $this->removeExcludedKeys($this->changedData);

        $data = $this->hoClient->apiRequest([
            'Method'     => $this->methods['create'],
            'Target'     => $this->target,
            'data'       => $dataForCreate,
            'invoice_id' => $this->invoice_id,
        ]);

        $this->parentEntity && $this->parentEntity->reload();

        $this->hoClient->trigger($this->triggerTarget . '.create.after', [$this, &$this->changedData]);

        // Because HO return only ID
        $this->origData = array_merge($this->origData, $dataForCreate);
        $this->origData['id'] = $data[0];
        $this->changedData = [];

        return $this;
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $this->hoClient->trigger($this->triggerTarget . '.delete.before', [$this, &$this->changedData]);

        $data = $this->remove();
        $this->parentEntity->reload();

        $this->hoClient->trigger($this->triggerTarget . '.delete.after', [$this, &$this->changedData]);

        return $data;
    }

    // TODO: think about naming
    private function remove()
    {
        return $this->hoClient->apiRequest([
            'Method' => $this->methods['delete'],
            'Target' => $this->target,
            'id'     => $this->id,
        ]);
    }
}
