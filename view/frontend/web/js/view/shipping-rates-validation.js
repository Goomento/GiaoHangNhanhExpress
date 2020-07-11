/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'Goomento_GiaoHangNhanhExpress/js/model/shipping-rates-validator',
    'Goomento_GiaoHangNhanhExpress/js/model/shipping-rates-validation-rules'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    ghnShippingRatesValidator,
    ghnExpressShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('ghn_express', ghnShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('ghn_express', ghnExpressShippingRatesValidationRules);

    return Component;
});
