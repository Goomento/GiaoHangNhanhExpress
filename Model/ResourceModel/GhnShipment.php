<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\ResourceModel;

/**
 * Class GhnShipment
 * @package Goomento\GiaoHangNhanhExpress\Model\ResourceModel
 */
class GhnShipment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('ghn_shipment', 'entity_id');
    }
}
