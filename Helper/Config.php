<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Helper;



use Magento\Framework\App\Helper\Context;

/**
 * Class Config
 * @package Goomento\GiaoHangNhanhExpress\Helper
 * @method static staticApiUrl();
 * @method static staticIsSandbox();
 * @method static staticCanShow();
 */
class Config extends \Goomento\Base\Helper\AbstractConfig
{
    const CODE = 'ghn_express';

    public function __construct(Context $context, array $scope = [])
    {
        parent::__construct($context, ['carriers', self::CODE]);
    }

    public function isSandbox()
    {
        return $this->configGet('environment')==='sandbox';
    }

    public function apiUrl()
    {
        $url = $this->isSandbox() ? $this->configGet('sandbox_endpoint') : $this->configGet('production_endpoint');
        $url = rtrim($url, '\\/') . '/shiip/public-api';
        return $url;
    }

    /**
     * @return bool
     */
    public function canShow()
    {
        return $this->isActive() && $this->configGetBool('showmethod');
    }
}
