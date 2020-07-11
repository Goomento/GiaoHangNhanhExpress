<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Source;

/**
 * Class PaymentType
 * @package Goomento\GiaoHangNhanhExpress\Model\Source
 */
class PaymentType implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Shop/Seller')],
            ['value' => 2, 'label' => __('Buyer/Consignee')],
        ];
    }
}
