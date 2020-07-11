<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Controller\Adminhtml\Shipment;

use Goomento\Base\Http\ClientException;
use Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Sync
 * @package Goomento\GiaoHangNhanhExpress\Controller\Adminhtml\Shipment
 */
class Sync extends AbstractShipment
{
    /**
     * @var GhnShipmentRepository
     */
    protected $ghnShipmentRepository;

    /**
     * Sync constructor.
     * @param Action\Context $context
     * @param GhnShipmentRepository $ghnShipmentRepository
     */
    public function __construct(
        Action\Context $context,
        GhnShipmentRepository $ghnShipmentRepository
    ) {
        parent::__construct($context);
        $this->ghnShipmentRepository = $ghnShipmentRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('order_id')) {
            $order_id = $this->getRequest()->getParam('order_id');
            $ghnShipment = $this->ghnShipmentRepository->getByOrder($order_id);
            try {
                $ghnShipment->sync();
                $this->messageManager->addSuccessMessage(__("Sync success!"));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__("Sync failed!"));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
