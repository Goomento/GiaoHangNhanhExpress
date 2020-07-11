<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Block\Adminhtml;

use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\UrlInterface;

/**
 * Class JsConfig
 * @package Goomento\GiaoHangNhanhExpress\Block\Adminhtml
 */
class JsConfig extends \Magento\Backend\Block\Template implements RendererInterface
{
    /**
     * @var string
     */
    protected $_template = "Goomento_GiaoHangNhanhExpress::system/config/js.phtml";

    /**
     * @var UrlInterface
     */
    protected $frontendUrl;

    /**
     * JsConfig constructor.
     * @param Template\Context $context
     * @param UrlInterface $frontendUrl
     * @param array $data
     */
    public function __construct(Template\Context $context, UrlInterface $frontendUrl, array $data = [])
    {
        parent::__construct($context, $data);
        $this->frontendUrl = $frontendUrl;
    }

    /**
     * @return string[]
     */
    public function getSharedApiUrl()
    {
        $sharedUrl = [
            'province' => Config::CODE . \Goomento\GiaoHangNhanhExpress\Controller\Shared\Province::getSlug(),
            'ward' =>  Config::CODE . \Goomento\GiaoHangNhanhExpress\Controller\Shared\Ward::getSlug(),
            'district' =>  Config::CODE . \Goomento\GiaoHangNhanhExpress\Controller\Shared\District::getSlug(),
            'station' =>  Config::CODE . \Goomento\GiaoHangNhanhExpress\Controller\Shared\Station::getSlug(),
            'store' =>  Config::CODE . \Goomento\GiaoHangNhanhExpress\Controller\Shared\Store::getSlug(),
        ];

        foreach ($sharedUrl as &$url) {
            $url = $this->frontendUrl->getUrl($url, [
                '_nosid' => true,
            ]);
        }

        return $sharedUrl;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }
}
