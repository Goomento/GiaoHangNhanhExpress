<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Source;

/**
 * Class CostType
 * @package Goomento\GiaoHangNhanhExpress\Model\Source
 */
class CostType implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Fixed fee')],
            ['value' => 2, 'label' => __('Per product')],
        ];
    }
}
