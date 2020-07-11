<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Controller\Shared;


use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Goomento\GiaoHangNhanhExpress\Helper\Logger;
use Magento\Framework\App\ResponseInterface;

/**
 * Class Store
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
class Store extends AbstractSharedController
{
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!Config::staticIsActive() || !$this->isGet()) {
            return $this->responseError();
        }

        $data = [];
        try {
            $stores = $this->commandPool->get('store')->build();
            foreach ($stores['shops'] as $store) {
                $data[$store['_id']] = $store['name'] . ': ' . $store['address'];
            }
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            $data = [];
        }

        return $this->responseOk($data);
    }
}
