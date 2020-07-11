<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http\Request;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class AbstractRequest
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request
 */
abstract class AbstractRequest implements \Goomento\Base\Model\BuilderInterface
{
    const PROVINCE_ID = 'province_id';
    const STORE_ID = 'store_id';
    const WARD_CODE = 'ward_code';
    const DISTRICT_ID = 'district_id';
    const API_STORE = 'v2/shop/all';
    const API_PROVINCE = 'master-data/province';
    const API_DISTRICT = 'master-data/district';
    const API_WARD = 'master-data/ward?district_id';
    const API_STATION = 'v2/station/get';
    const API_ORDER_CREATE = 'v2/shipping-order/create';
    const API_ORDER_INFO = 'v2/shipping-order/detail';
    const ORDER = 'order';
    const GHN_SHIPMENT = 'ghn_shipment';

    /**
     * @param array $data
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    protected static function readOrder(array $data)
    {
        return $data[self::ORDER] ?? null;
    }

    /**
     * @param array $data
     * @return \Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment
     */
    protected static function readGhnShipment(array $data)
    {
        return $data[self::GHN_SHIPMENT] ?? null;
    }
}
