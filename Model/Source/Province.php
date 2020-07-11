<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Source;

use Goomento\GiaoHangNhanhExpress\Helper\Config;

/**
 * Class Province
 * @package Goomento\GiaoHangNhanhExpress\Model\Source
 */
class Province implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => Config::staticConfigGet('store_province'), 'label' => '-']
        ];
    }
}
