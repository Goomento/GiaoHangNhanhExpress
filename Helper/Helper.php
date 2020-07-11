<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Helper;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Helper
 * @package Goomento\MomoPayment\Helper
 * @method staticConvertCurrency($amount, $from, $to = null)
 */
class Helper extends \Goomento\Base\Helper\AbstractHelper
{
    use \Goomento\Base\Traits\InstanceManager;
    use \Goomento\Base\Traits\Rate;
    use \Goomento\Base\Traits\Datetime;

    /**
     * @param \Magento\Sales\Model\Order $order
     * @throws LocalizedException
     */
    public static function assertOrderLocateVN(\Magento\Sales\Model\Order $order)
    {
        if ($order->getShippingAddress()->getCountryId()!=='VN') {
            throw new LocalizedException(__("Parcels will not ship outside Vietnam"));
        }

        try {
            /**
             * Check convert
             */
            self::staticConvertCurrency($order->getTotalDue(), $order->getOrderCurrencyCode(), 'VND');
        } catch (\Exception $e) {
            throw new LocalizedException(__('Something went wrong when convert currency to VND, please setup your currency'));
        }
    }
}
