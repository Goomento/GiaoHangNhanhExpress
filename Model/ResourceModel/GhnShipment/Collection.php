<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\ResourceModel\GhnShipment;

/**
 * Class Collection
 * @package Goomento\GiaoHangNhanhExpress\Model\ResourceModel\GhnShipment
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'ghn_shipment_collection';
    protected $_eventObject = 'ghn_shipment_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Goomento\GiaoHangNhanhExpress\Model\GhnShipment', 'Goomento\GiaoHangNhanhExpress\Model\ResourceModel\GhnShipment');
    }
}
