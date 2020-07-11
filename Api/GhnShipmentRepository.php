<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */


namespace Goomento\GiaoHangNhanhExpress\Api;

/**
 * Interface GhnShipmentRepository
 * @package Goomento\GiaoHangNhanhExpress\Api
 */
interface GhnShipmentRepository
{
    /**
     * @param int $orderId
     * @return Data\GhnShipment
     */
    public function getByOrder(int $orderId);

    /**
     * @param Data\GhnShipment $shipment
     * @return mixed
     */
    public function save(Data\GhnShipment $shipment);

    /**
     * @param string $codeCode
     * @return mixed
     */
    public function getByGhnOrder(string $codeCode);

    /**
     * @param Data\GhnShipment $shipment
     * @return mixed
     */
    public function sync(Data\GhnShipment $shipment);

    /**
     * @return mixed
     */
    public function getSyncList();

    /**
     * @return mixed
     */
    public function syncList();
}
