/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (http://goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 */

define([], function () {
    'use strict';

    return {
        /**
         * @return {Object}
         */
        getRules: function () {
            return {
                'postcode': {
                    'required': false
                },
                'country_id': {
                    'required': true
                },
                'city': {
                    'required': false
                }
            };
        }
    };
});
