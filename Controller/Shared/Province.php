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
 * Class Province
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
class Province extends AbstractSharedController
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
            $provinces = $this->commandPool->get('province')->build();
            foreach ($provinces as $province) {
                $data[$province['ProvinceID']] = $province['ProvinceName'];
            }
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            $data = [];
        }

        return $this->responseOk($data);
    }
}
