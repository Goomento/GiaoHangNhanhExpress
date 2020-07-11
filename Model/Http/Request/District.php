<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http\Request;


use Goomento\GiaoHangNhanhExpress\Model\Http\TransferFactory;
use Magento\Framework\HTTP\ZendClient;

/**
 * Class District
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request
 */
class District extends AbstractRequest
{

    public function build(array $buildSubject)
    {
        return [
            TransferFactory::URI => self::API_DISTRICT,
            TransferFactory::METHOD => ZendClient::POST,
            TransferFactory::BODY => [
                self::PROVINCE_ID => $buildSubject[self::PROVINCE_ID] ?? ''
            ],
        ];
    }
}
