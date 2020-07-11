<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http\Request;

use Goomento\GiaoHangNhanhExpress\Model\Http\TransferFactory;

/**
 * Class Store
 * @package Goomento\GiaoHangNhanhExpress\Model\Http\Request
 */
class Store extends AbstractRequest
{

    public function build(array $buildSubject)
    {
        return [
            TransferFactory::URI => self::API_STORE
        ];
    }
}
