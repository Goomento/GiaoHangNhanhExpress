<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Helper;


use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

/**
 * Class Logger
 * @package Goomento\GiaoHangNhanhExpress\Helper
 */
class Logger extends \Goomento\Base\Helper\Logger
{
    public function __construct(Context $context, LoggerInterface $logger = null, string $log_name = null)
    {
        parent::__construct($context, $logger, $log_name);
    }
}
