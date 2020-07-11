<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http\Request\Order;


use Goomento\GiaoHangNhanhExpress\Model\Http\Request\AbstractRequest;
use Goomento\GiaoHangNhanhExpress\Model\Http\TransferFactory;
use Magento\Framework\HTTP\ZendClient;

/**
 * Class Sync
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request\Order
 */
class Sync extends AbstractRequest
{
    public function build(array $buildSubject)
    {
        $shipment = self::readGhnShipment($buildSubject);
        return [
            TransferFactory::URI => self::API_ORDER_INFO,
            TransferFactory::METHOD => ZendClient::POST,
            TransferFactory::BODY => [
                'order_code' => $shipment->getTracking()
            ]
        ];
    }
}
