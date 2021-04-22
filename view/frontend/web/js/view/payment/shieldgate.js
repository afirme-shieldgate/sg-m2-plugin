/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'https://code.jquery.com/jquery-1.11.3.min.js',
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';

        const config = window.checkoutConfig.payment;

        if (config.shieldgate_card.is_active) {
            rendererList.push(
                {
                    type: 'shieldgate_card',
                    component: 'Shieldgate_PaymentGateway/js/view/payment/method-renderer/shieldgate_card'
                }
            );
        }
        if (config.shieldgate_ltp.is_active) {
            rendererList.push(
                {
                    type: 'shieldgate_ltp',
                    component: 'Shieldgate_PaymentGateway/js/view/payment/method-renderer/shieldgate_ltp'
                }
            );
        }
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
