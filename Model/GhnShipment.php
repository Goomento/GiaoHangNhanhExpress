<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model;


use Goomento\GiaoHangNhanhExpress\Helper\Helper;
use Goomento\GiaoHangNhanhExpress\Helper\Logger;

/**
 * Class GhnShipment
 * @package Goomento\GiaoHangNhanhExpress\Model
 */
class GhnShipment extends \Magento\Framework\Model\AbstractModel implements
    \Magento\Framework\DataObject\IdentityInterface,
    \Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment
{
    const CACHE_TAG = 'ghn_shipment';

    protected $_cacheTag = 'ghn_shipment';

    protected $_eventPrefix = 'ghn_shipment';

    protected function _construct()
    {
        $this->_init('Goomento\GiaoHangNhanhExpress\Model\ResourceModel\GhnShipment');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        return [];
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function sync()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try {
            if ($this->getId() && $this->getTracking()) {
                $this->onSyncGhnOrder();
            } else {
                $this->onCreateGhnOrder();
            }
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            throw $e;
        }

        /** @var \Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository $repo */
        $repo = $objectManager->get(\Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository::class);
        return $repo->save($this);
    }

    protected function onCreateGhnOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool $commandPool */
        $commandPool = $objectManager->get(\Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool::class);
        $result = $commandPool->get('order/create')->build([
            'ghn_shipment' => $this,
        ]);
        $this->setOrderCode($result['order_code']);
        $this->setResponseData(\Zend_Json::encode($result));
        $this->setLastSync();
        $this->setExpectedDeliveryTime($result['expected_delivery_time']);
    }

    /**
     * @return array|mixed|null
     * @throws \Zend_Json_Exception
     */
    public function getResponseData()
    {
        $data = $this->getData('response_data');
        if (is_string($data)) {
            $data = \Zend_Json::decode($data);
        }

        return $data;
    }

    /**
     * @return array|mixed|null
     * @throws \Zend_Json_Exception
     */
    public function getSendData()
    {
        $data = $this->getData('send_data');
        if (is_string($data)) {
            $data = \Zend_Json::decode($data);
        }

        return $data;
    }

    public function setLastSync()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\Stdlib\DateTime\DateTime $dataTime */
        $dataTime = $objectManager->get(\Magento\Framework\Stdlib\DateTime\DateTime::class);
        $this->setData('last_sync', $dataTime->gmtDate());
    }

    /**
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    protected function onSyncGhnOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool $commandPool */
        $commandPool = $objectManager->get(\Goomento\GiaoHangNhanhExpress\Model\ClientCommandPool::class);
        $result = $commandPool->get('order/sync')->build([
            'ghn_shipment' => $this,
        ]);
        $this->setLastSync();
        $this->setStatus($result['status']);
        $this->setLog(\Zend_Json::encode($result['log'] ?? []));
    }

    /**
     * @return array|string
     */
    public function getTracking()
    {
        return $this->getData('order_code');
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function getLastSync()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timeZone */
        $timeZone = $objectManager->get(\Magento\Framework\Stdlib\DateTime\TimezoneInterface::class);
        return $timeZone->date(new \DateTime($this->getData('last_sync') ?? ''))->format('m/d/y H:i:s');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return strtoupper($this->getData('status'));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getExpectedDeliveryTime()
    {
        return Helper::formatDateTime($this->getData('expected_delivery_time') ?? '');
    }

    public function getLog()
    {
        $log = $this->getData('log');
        $string_log = '';
        if ($log) {
            $log = \Zend_Json::decode($log);
            foreach ($log as $time) {
                $datetime = Helper::formatDateTime($time['updated_date']);
                $string_log .= "{$time['status']}: {$datetime}" . '</br>';
            }
        }

        return $string_log;
    }
}
