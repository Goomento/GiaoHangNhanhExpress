<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model;

use Goomento\GiaoHangNhanhExpress\Api\Data;
use Goomento\GiaoHangNhanhExpress\Helper\GhnObject;
use Goomento\GiaoHangNhanhExpress\Helper\Logger;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GhnShipmentRepository
 * @package Goomento\GiaoHangNhanhExpress\Model
 */
class GhnShipmentRepository implements \Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository
{
    /**
     * @var ResourceModel\GhnShipmentFactory
     */
    protected $ghnShipmentResourceFactory;
    /**
     * @var GhnShipmentFactory
     */
    protected $ghnShipmentFactory;
    /**
     * @var ResourceModel\GhnShipment\CollectionFactory
     */
    protected $ghnShipmentCollectionFactory;

    public function __construct(
        ResourceModel\GhnShipmentFactory $ghnShipmentResourceFactory,
        ResourceModel\GhnShipment\CollectionFactory $ghnShipmentCollectionFactory,
        GhnShipmentFactory $ghnShipmentFactory
    ) {
        $this->ghnShipmentResourceFactory = $ghnShipmentResourceFactory;
        $this->ghnShipmentFactory = $ghnShipmentFactory;
        $this->ghnShipmentCollectionFactory = $ghnShipmentCollectionFactory;
    }

    /**
     * @return ResourceModel\GhnShipment
     */
    protected function getResource()
    {
        return $this->ghnShipmentResourceFactory->create();
    }

    /**
     * @return GhnShipment
     */
    protected function getModel()
    {
        return $this->ghnShipmentFactory->create();
    }

    /**
     * @return ResourceModel\GhnShipment\Collection
     */
    protected function getCollection()
    {
        return $this->ghnShipmentCollectionFactory->create();
    }

    /**
     * @param int $orderId
     * @return GhnShipment
     * @throws NoSuchEntityException
     */
    public function getByOrder(int $orderId)
    {
        $shipment = $this->getModel();
        $this->getResource()->load($shipment, $orderId, 'order_id');
        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Unable to find GHN shipment with ID "%1"', $orderId));
        }

        return $shipment;
    }

    /**
     * @param string $ghnOrderCode
     * @return GhnShipment
     * @throws NoSuchEntityException
     */
    public function getByGhnOrder(string $ghnOrderCode)
    {
        $shipment = $this->getModel();
        $this->getResource()->load($shipment, $ghnOrderCode, 'order_code');
        if (!$shipment->getId()) {
            throw new NoSuchEntityException(__('Unable to find GHN shipment with code "%1"', $ghnOrderCode));
        }

        return $shipment;
    }

    /**
     * @param \Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment $order
     * @return ResourceModel\GhnShipment
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment $order)
    {
        return $this->getResource()->save($order);
    }

    /**
     * @param Data\GhnShipment $shipment
     * @return mixed|void
     */
    public function sync(Data\GhnShipment $shipment)
    {
        return $shipment->sync();
    }

    /**
     * @return GhnShipment[]|mixed
     */
    public function getSyncList()
    {
        return $this->getCollection()
            ->addFieldToFilter('status', ['nin' => GhnObject::finishedStatus()])
            ->getItems();
    }

    /**
     * @return mixed|void
     */
    public function syncList()
    {
        $items = $this->getSyncList();
        foreach ($items as $model) {
            try {
                $model->sync();
            } catch (\Exception $e) {
                Logger::staticError($e->getMessage());
            }
        }
    }
}
