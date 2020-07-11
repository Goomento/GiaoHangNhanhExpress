<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Controller\Shared;


use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Magento\Framework\App\ResponseInterface;

/**
 * Class District
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
class District extends AbstractSharedController
{
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!Config::staticIsActive() || !$this->isGet() || !$this->getRequest()->getParam('province_id')) {
            return $this->responseError();
        }

        $province_id = $this->getRequest()->getParam('province_id');
        $data = [];

        try {
            $districts = $this->commandPool->get('district')->build([
                'province_id' => (int) $province_id,
            ]);

            foreach ($districts as $district) {
                $data[$district['DistrictID']] = $district['DistrictName'];
            }

        } catch (\Exception $e) {
            $data = [];
        }

        return $this->responseOk($data);
    }
}
