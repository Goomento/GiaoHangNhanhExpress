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
 * Class Station
 * @package Goomento\GiaoHangNhanhExpress\Controller\Shared
 */
class Station extends AbstractSharedController
{
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!Config::staticIsActive()
            || !$this->isGet()
            || !$this->getRequest()->getParam('district_id')
            || !$this->getRequest()->getParam('ward_code')
        ) {
            return $this->responseError();
        }

        $district_id = $this->getRequest()->getParam('district_id');
        $ward_code = $this->getRequest()->getParam('ward_code');
        $data = [];

        try {
            $districts = $this->commandPool->get('station')->build([
                'district_id' => (string) $district_id,
                'ward_code' => (string) $ward_code,
            ]);

            foreach ($districts as $district) {
                $data[$district['locationId']] = $district['locationName'];
            }

        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            $data = [];
        }

        return $this->responseOk($data);
    }
}
