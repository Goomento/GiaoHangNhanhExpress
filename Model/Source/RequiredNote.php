<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Source;

/**
 * Class RequiredNote
 * @package Goomento\GiaoHangNhanhExpress\Model\Source
 */
class RequiredNote implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 'CHOTHUHANG', 'label' => 'CHOTHUHANG'],
            ['value' => 'CHOXEMHANGKHONGTHU', 'label' => 'CHOXEMHANGKHONGTHU'],
            ['value' => 'KHONGCHOXEMHANG', 'label' => 'KHONGCHOXEMHANG'],
        ];
    }
}
