<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Helper;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class GhnObject
 * @package Goomento\GiaoHangNhanhExpress\Helper
 */
class GhnObject
{
    /**
     * @return string[]
     */
    public static function finishedStatus()
    {
        return [
            'cancel',
            'returned',
            'delivered',
        ];
    }

    /**
     * @param $orderCode
     * @return string
     */
    public static function getTrackingUrl($orderCode)
    {
        return sprintf('https://donhang.ghn.vn/?order_code=%s', $orderCode);
    }

    /**
     * @param string $phone
     * @return bool
     * @throws LocalizedException
     */
    public static function assertPhoneOk(string $phone)
    {
        if ((string)substr($phone, 0, 1) === '0'
            || in_array(substr($phone, 0, 2), ['84', '+8'])) {
            return true;
        }
        throw new LocalizedException(__("Phone must be start by +84, 84, 0"));
    }
}
