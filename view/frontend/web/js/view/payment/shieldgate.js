/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'jQuery1113',
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
