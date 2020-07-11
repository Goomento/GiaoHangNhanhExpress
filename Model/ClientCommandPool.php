<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model;


use Magento\Framework\Exception\NotFoundException;

/**
 * Class ClientCommandPool
 * @package Goomento\GiaoHangNhanhExpress\Model
 */
class ClientCommandPool extends \Goomento\Base\Model\BuilderComposite
{
    /**
     * @param $commandCode
     * @return \Goomento\GiaoHangNhanhExpress\Model\Http\ClientCommand
     * @throws NotFoundException
     */
    public function get($commandCode)
    {
        if (!isset($this->builders[$commandCode])) {
            throw new NotFoundException(
                __('The "%1" command doesn\'t exist. Verify the command and try again.', $commandCode)
            );
        }

        return $this->builders[$commandCode];
    }

    public function build(array $buildSubject)
    {
        return [];
    }
}
