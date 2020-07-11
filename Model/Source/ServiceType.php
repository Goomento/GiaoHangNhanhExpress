<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Source;

/**
 * Class ServiceType
 * @package Goomento\GiaoHangNhanhExpress\Model\Source
 */
class ServiceType implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Other service is not available
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
//            ['value' => 1, 'label' => __('Express')],
            ['value' => 2, 'label' => __('Standard')],
//            ['value' => 3, 'label' => __('Saving')],
        ];
    }
}
