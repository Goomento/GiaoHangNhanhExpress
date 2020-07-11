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
 * Class Station
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request
 */
class Station extends AbstractRequest
{

    public function build(array $buildSubject)
    {
        return [
            TransferFactory::URI => self::API_STATION,
            TransferFactory::METHOD => ZendClient::POST,
            TransferFactory::BODY => [
                self::DISTRICT_ID => $buildSubject[self::DISTRICT_ID] ?? '',
                self::WARD_CODE => $buildSubject[self::WARD_CODE] ?? '',
                'offset' => 0,
                'limit' => 1000,
            ],
        ];
    }
}
