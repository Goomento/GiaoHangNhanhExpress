<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Controller\Shared;

use Magento\Framework\App\Action\Context;

/**
 * Class AbstractSharedController
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
abstract class AbstractSharedController extends \Goomento\Base\Controller\AbstractApiController
{
    /**
     * @var \Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool
     */
    protected $commandPool;

    /**
     * AbstractSharedController constructor.
     * @param Context $context
     * @param \Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool $commandPool
     */
    public function __construct(
        Context $context,
        \Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool $commandPool
    ) {
        parent::__construct($context);
        $this->commandPool = $commandPool;
    }
}
