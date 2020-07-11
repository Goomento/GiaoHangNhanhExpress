<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Controller\Adminhtml\Shipment;

use Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment;
use Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipmentFactory;
use Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository;
use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Goomento\GiaoHangNhanhExpress\Helper\GhnObject;
use Goomento\GiaoHangNhanhExpress\Helper\Helper;
use Goomento\GiaoHangNhanhExpress\Helper\Logger;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\JsonConverter;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Magento\Sales\Model\Order\Shipment\ShipmentValidatorInterface;
use Magento\Sales\Model\Order\Shipment\Validation\QuantityValidator;
use Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader;
use Magento\Shipping\Model\Shipping\LabelGenerator;

/**
 * Class Create
 * @package Goomento\GiaoHangNhanhExpress\Controller\Adminhtml\Shipment
 */
class Create extends AbstractShipment
{
    /**
     * @var ShipmentLoader
     */
    protected $shipmentLoader;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var ShipmentValidatorInterface
     */
    protected $shipmentValidator;
    /**
     * @var ShipmentSender
     */
    protected $shipmentSender;
    /**
     * @var LabelGenerator
     */
    protected $labelGenerator;
    /**
     * @var GhnShipmentFactory
     */
    protected $ghnShipmentFactory;
    /**
     * @var GhnShipmentRepository
     */
    protected $ghnShipmentRepository;

    /**
     * Create constructor.
     * @param Action\Context $context
     * @param GhnShipmentFactory $ghnShipmentFactory
     * @param GhnShipmentRepository $ghnShipmentRepository
     * @param ShipmentLoader $shipmentLoader
     * @param OrderRepositoryInterface $orderRepository
     * @param LabelGenerator $labelGenerator
     * @param ShipmentSender $shipmentSender
     * @param ShipmentValidatorInterface $shipmentValidator
     */
    public function __construct(
        Action\Context $context,
        GhnShipmentFactory $ghnShipmentFactory,
        GhnShipmentRepository $ghnShipmentRepository,
        ShipmentLoader $shipmentLoader,
        OrderRepositoryInterface $orderRepository,
        LabelGenerator $labelGenerator,
        ShipmentSender $shipmentSender,
        ShipmentValidatorInterface $shipmentValidator
    ) {
        parent::__construct($context);
        $this->shipmentLoader = $shipmentLoader;
        $this->ghnShipmentFactory = $ghnShipmentFactory;
        $this->ghnShipmentRepository = $ghnShipmentRepository;
        $this->orderRepository = $orderRepository;
        $this->labelGenerator = $labelGenerator;
        $this->shipmentSender = $shipmentSender;
        $this->shipmentValidator = $shipmentValidator;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $order_id = $this->getRequest()->getParam('order_id');
        if ($this->getRequest()->isPost() && $order_id) {
            try {
                /** @var GhnShipment $ghnShipment */
                $ghnShipment = $this->processGhnOrder();
                /** @var Order $order */
                $order = $this->orderRepository->get($this->getRequest()->getParam('order_id'));
                $shipmentOrderItems = [];
                foreach ($this->getOrderItems($order) as $orderItem) {
                    $shipmentOrderItems[$orderItem->getItemId()] = $orderItem->getQtyToShip();
                }
                $this->shipmentLoader->setOrderId($order_id);
                $this->shipmentLoader->setShipmentId(null);
                $this->shipmentLoader->setShipment([
                    'items' => $shipmentOrderItems,
                    'comment_text' => $this->getRequest()->getParam('note'),
                ]);
                $this->shipmentLoader->setTracking($this->getTracking($ghnShipment));
                $shipment = $this->shipmentLoader->load();
                if (!$shipment) {
                    throw new NoSuchEntityException();
                }

                if (!empty($data['note'])) {
                    $shipment->addComment(
                        $data['note'],
                        false,
                        true
                    );
                    // TODO: Change this logical
                    $shipment->setCustomerNote($data['note']);
                    $shipment->setCustomerNoteNotify(true);
                }

                $validationResult = $this->shipmentValidator->validate($shipment, [QuantityValidator::class]);

                if ($validationResult->hasMessages()) {
                    $this->messageManager->addErrorMessage(
                        __("Shipment Document Validation Error(s):\n" . implode("\n", $validationResult->getMessages()))
                    );
                    throw new LocalizedException(__("Your shipment not validate!"));
                }
                $shipment->register();
                $shipment->getOrder()->setCustomerNoteNotify(true);
                $this->saveShipment($shipment);
                $this->messageManager->addSuccessMessage(__("Your shipment successfully send to GHN"));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                Logger::staticError($e->getMessage());
                $this->messageManager->addErrorMessage(__('Something went wrong'));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * @param GhnShipment $ghnShipment
     * @return array
     */
    protected function getTracking(GhnShipment $ghnShipment)
    {
        return [[
            'carrier_code' => Config::CODE,
            'title' => Config::staticConfigGet('title'),
            'number' => $ghnShipment->getTracking()
        ]];
    }

    /**
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    protected function processGhnOrder()
    {
        $order_id = (int)$this->getRequest()->getParam('order_id');
        /** @var Order $order */
        $order = $this->orderRepository->get($order_id);
        if (!$order->getId()) {
            throw new NoSuchEntityException(__("Invalid order Id: %s", $order_id));
        }
        try {
            try {
                $ghnShipment = $this->ghnShipmentRepository->getByOrder($order_id);
            } catch (NoSuchEntityException $e) {
                $ghnShipment = null;
            } catch (\Exception $e) {
                throw $e;
            }
            if (is_null($ghnShipment)) {
                /** @var \Goomento\GiaoHangNhanhExpress\Api\Data\GhnShipment $ghnShipment */
                $ghnShipment = $this->ghnShipmentFactory->create();
            }

            $ghnShipment->setOrderId($order_id);
            $ghnShipment->setOrderCode(null);
            $ghnShipment->setStatus(GhnShipment::STATUS_PENDING);
            $orderStoreId = $order->getStoreId();
            $requestData = $this->getRequest()->getParams();
            foreach (['key', 'text_data', 'form_key'] as $key) {
                if (isset($requestData[$key])) {
                    unset($requestData[$key]);
                }
            }
            foreach (['to_district_id', 'cod_amount', 'weight', 'length', 'width', 'height'] as $key) {
                if (isset($requestData[$key])) {
                    $requestData[$key] = (int) $requestData[$key];
                }
            }
            $sendData = array_merge(
                $this->getStoreConfig($orderStoreId),
                $requestData,
                $this->getParcelContent($order),
                $this->getCustomerData($order)
            );
            $ghnShipment->setSendData(JsonConverter::convert($sendData));
            $this->ghnShipmentRepository->save($ghnShipment);
        } catch (LocalizedException $e) {
            Logger::staticError($e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            throw new LocalizedException(__("Something went wrong when save order to database."));
        }

        try {
            $ghnShipment->sync();
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception("Something when wrong when sync order");
        }

        return $ghnShipment;
    }

    /**
     * Save shipment and order in one transaction
     *
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @return $this
     */
    protected function saveShipment($shipment)
    {
        $shipment->getOrder()->setIsInProcess(true);
        $transaction = $this->_objectManager->create(
            \Magento\Framework\DB\Transaction::class
        );
        $transaction->addObject(
            $shipment
        )->addObject(
            $shipment->getOrder()
        )->save();

        return $this;
    }

    /**
     * @param $storeId
     * @return array
     */
    protected function getStoreConfig($storeId)
    {
        return [
            'return_phone' => Config::staticConfigGet('store_phone', '', $storeId),
            'shop_id' => (int) Config::staticConfigGet('store_id', 0, $storeId),
            'return_address' => Config::staticConfigGet('store_address', '', $storeId),
            'return_district_id' => (int) Config::staticConfigGet('store_district', 0, $storeId),
            'return_ward_code' => Config::staticConfigGet('store_ward', 0, $storeId),
            'pick_station_id' => (int) Config::staticConfigGet('ghn_station', 0, $storeId),
            'payment_type_id' => (int) Config::staticConfigGet('payment_type_id', 0, $storeId),
            'required_note' => Config::staticConfigGet('shipping_required_note', 0, $storeId),
            'service_type_id' => (int) Config::staticConfigGet('service_type_id', 0, $storeId),
            'service_id' => (int) Config::staticConfigGet('service_id', 0, $storeId),
            // TODO: Need an API to check Service ID? Maybe late
        ];
    }

    /**
     * @param Order $order
     * @return array|Order\Item[]
     */
    protected function getOrderItems(Order $order)
    {
        return $order->getAllItems() ?? [];
    }

    /**
     * @param Order $order
     * @return string[]
     */
    protected function getParcelContent(Order $order)
    {
        $content = '';
        foreach ($this->getOrderItems($order) as $item) {
            if ($item->getId() && $item->canShip()) {
                $item_name = $item->getName() ?: 'N/A';
                $item_qyt = $item->getQtyToShip() ?: 'N/A';
                $content .= "{$item_name}: {$item_qyt}" . PHP_EOL;
            }
        }
        return [
            'content' => $content
        ];
    }

    /**
     * @param Order $order
     * @return array
     * @throws \Exception
     */
    protected function getCustomerData(Order $order)
    {
        $shippingAddress = $order->getShippingAddress();
        Helper::assertOrderLocateVN($order);
        GhnObject::assertPhoneOk($shippingAddress->getTelephone());
        return [
            'to_name' => $shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname(),
            'to_phone' => $shippingAddress->getTelephone(),
            'to_address' => implode(', ', [
                implode(' | ', $shippingAddress->getStreet()),
                $shippingAddress->getCity()
            ]),
        ];
    }
}
