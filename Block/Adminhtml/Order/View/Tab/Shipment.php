<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Block\Adminhtml\Order\View\Tab;

use Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository;
use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order;

/**
 * Class Shipment
 * @package Goomento\GiaoHangNhanhExpress\Block\Adminhtml\Order\View\Tab
 */
class Shipment extends \Magento\Backend\Block\Template implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var UrlInterface
     */
    protected $frontendUrl;
    /**
     * @var GhnShipmentRepository
     */
    protected $ghnShipmentRepository;

    /**
     * Shipment constructor.
     * @param Context $context
     * @param UrlInterface $frontendUrl
     * @param GhnShipmentRepository $ghnShipmentRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $frontendUrl,
        GhnShipmentRepository $ghnShipmentRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->frontendUrl = $frontendUrl;
        $this->ghnShipmentRepository = $ghnShipmentRepository;
    }

    /**
     * @return Shipment
     */
    protected function _beforeToHtml()
    {
        /** @var Order $order */
        $order = \Goomento\Base\Helper\Registry::staticRegistry('current_order');
        $ghn_shipment = null;
        try {
            $ghn_shipment = $this->ghnShipmentRepository->getByOrder($order->getId());
        } catch (\Exception $e) {
            $ghn_shipment = null;
        }

        \Goomento\Base\Helper\Registry::staticRegister('ghn_shipment', $ghn_shipment);

        return parent::_beforeToHtml();
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabLabel()
    {
        return __("GHN Shipment");
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabTitle()
    {
        return __("GHN Shipment");
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return Config::staticIsActive();
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
