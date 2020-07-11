<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Block\Adminhtml\Order\View\Tab;

use Goomento\GiaoHangNhanhExpress\Helper\Helper;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order;

/**
 * Class Editable
 * @package Goomento\GiaoHangNhanhExpress\Block\Adminhtml\Order\View\Tab
 */
class Editable extends Generic
{
    /**
     * Prepare form fields
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Order $order */
        $order = $this->_coreRegistry->registry('current_order');
        if (!$order->canShip() || Helper::assertOrderLocateVN($order)) {
            return null;
        }

        $formData = new DataObject($this->getDefaultData());

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset(
            'update_fieldset',
            ['legend' => __('Send to GHN')]
        );

        $fieldset->addField(
            'province_id',
            'select',
            [
                'name'      => 'province_id',
                'label'     => __('Province'),
                'class' => 'validate-number',
                'required' => true,
                'options'  => []
            ]
        );

        $fieldset->addField(
            'to_district_id',
            'select',
            [
                'name'      => 'to_district_id',
                'label'     => __('District ID'),
                'class' => 'validate-number',
                'required' => true,
                'options'  => []
            ]
        );

        $fieldset->addField(
            'to_ward_code',
            'select',
            [
                'name'      => 'to_ward_code',
                'label'     => __('Ward Code'),
                'required' => true,
                'options'  => []
            ]
        );

        $fieldset->addField(
            'weight',
            'text',
            [
                'name'      => 'weight',
                'label'     => __('Weight (gram)'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'length',
            'text',
            [
                'name'      => 'length',
                'label'     => __('Length (cm)'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'width',
            'text',
            [
                'name'      => 'width',
                'label'     => __('Width (cm)'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'height',
            'text',
            [
                'name'      => 'height',
                'label'     => __('Height (cm)'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'cod_amount',
            'text',
            [
                'name'      => 'cod_amount',
                'class' => 'validate-number',
                'label'     => __('COD (VND)')
            ]
        );

        $fieldset->addField(
            'note',
            'textarea',
            [
                'name'      => 'note',
                'label'     => __('Note'),
            ]
        );
        $form->setValues($formData->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function getDefaultData()
    {
        /** @var Order $order */
        $order = $this->_coreRegistry->registry('current_order');

        return [
            'weight' => 200,
            'length' => 20,
            'width' => 20,
            'height' => 20,
            'cod_amount' => Helper::staticConvertCurrency(
                $order->getBaseTotalDue(),
                $order->getBaseCurrencyCode(),
                'VND'
            ),
        ];
    }
}
