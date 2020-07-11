<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http\Request\Order;


use Goomento\GiaoHangNhanhExpress\Helper\GhnObject;
use Goomento\GiaoHangNhanhExpress\Model\Http\Request\AbstractRequest;
use Goomento\GiaoHangNhanhExpress\Model\Http\TransferFactory;
use Magento\Framework\HTTP\ZendClient;

/**
 * Class Create
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request\Order
 */
class Create extends AbstractRequest
{
    /**
     * @param array $buildSubject
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Json_Exception
     */
    public function build(array $buildSubject)
    {
        $shipment = self::readGhnShipment($buildSubject);
        $data = $shipment->getData('send_data');
        $data = \Zend_Json::decode((string)$data);
        $shop_id = $data['shop_id'] ?? null;
        unset($data['shop_id']);
        GhnObject::assertPhoneOk($data['to_phone']);
        return [
            TransferFactory::URI => self::API_ORDER_CREATE,
            TransferFactory::HEADER => [
                'shopid' => $shop_id,
            ],
            TransferFactory::BODY => $data,
            TransferFactory::METHOD => ZendClient::POST
        ];
    }
}
