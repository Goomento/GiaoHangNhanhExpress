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
 * Class Ward
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
class Ward extends AbstractSharedController
{
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!Config::staticIsActive() || !$this->isGet() || !$this->getRequest()->getParam('district_id')) {
            return $this->responseError();
        }
        $district_id = $this->getRequest()->getParam('district_id');
        $data = [];
        try {
            $wards = $this->commandPool->get('ward')->build([
                'district_id' => (int) $district_id,
            ]);

            $data = [['value' => '', 'label' => '-']];
            foreach ($wards as $ward) {
                $data[$ward['WardCode']] = $ward['WardName'];
            }

        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            $data = [];
        }

        return $this->responseOk($data);
    }
}
