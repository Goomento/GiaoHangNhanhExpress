<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model;

use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;

/**
 * Class Carrier
 * @package Goomento\GiaoHangNhanhExpress\Model
 */
class Carrier extends AbstractCarrier implements \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = Config::CODE;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    protected $trackStatusFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;
    /**
     * @var \Magento\Sales\Model\AdminOrder\Create
     */
    protected $adminOrderCreation;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Sales\Model\AdminOrder\Create $adminOrderCreation
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\State $appState,
        \Magento\Sales\Model\AdminOrder\Create $adminOrderCreation,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->checkoutSession = $checkoutSession;
        $this->appState = $appState;
        $this->adminOrderCreation = $adminOrderCreation;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->trackStatusFactory = $trackStatusFactory;
    }

    /**
     * @param RateRequest $request
     * @return bool|\Magento\Shipping\Model\Rate\Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!Config::staticCanShow()) {
            return false;
        }

        try {
            /** @var \Magento\Shipping\Model\Rate\Result $result */
            $result = $this->rateResultFactory->create();

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
            $method = $this->rateMethodFactory->create();

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));

            /** @var Quote  $quote */
            $quote = $this->checkoutSession->getQuote();
            $shipping_total = null;
            $shipping_cost = (float)$this->getConfigData('shipping_cost');

            if ((int)$this->getConfigData('shipping_cost_type') === 2) {
                if (in_array($this->appState->getAreaCode(), ['admin', 'adminhtml'])) {
                    $items = $this->adminOrderCreation->getQuote()->getAllItems();
                } else {
                    $items = $quote->getAllItems();
                }
                /** @var \Magento\Quote\Model\Quote\Item $item */
                foreach ($items as $item) {
                    if (self::canShip($item)) {
                        $shipping_total += $shipping_cost*$item->getQty();
                    }
                }
            } else {
                $shipping_total += $shipping_cost;
            }

            $shipping_total += (float)$this->getConfigData('shipping_handler_cost');

            $method->setPrice($shipping_total);
            $method->setCost($shipping_total);

            $result->append($method);
        } catch (\Exception $e) {
            return false;
        }

        return $result;
    }

    /**
     * @param Quote\Item $item
     * @return bool
     */
    public static function canShip(\Magento\Quote\Model\Quote\Item $item)
    {
        return $item->getProductType()==='simple';
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => Config::staticConfigGet('title')];
    }

    /**
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * @param string $trackingNumber
     * @return bool|false|\Magento\Shipping\Model\Tracking\Result\Status|string
     */
    public function getTrackingInfo($trackingNumber)
    {
        $tracking = $this->trackStatusFactory->create();

        $url = \Goomento\GiaoHangNhanhExpress\Helper\GhnObject::getTrackingUrl($trackingNumber);

        $tracking->setData([
            'carrier' => $this->_code,
            'carrier_title' => $this->getConfigData('title'),
            'tracking' => $trackingNumber,
            'url' => $url,
        ]);

        return $tracking;
    }
}
