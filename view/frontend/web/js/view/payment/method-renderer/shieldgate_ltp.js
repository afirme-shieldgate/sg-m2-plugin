/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success',
        'mage/url',
        'mage/translate'
    ],
    function (Component, redirectOnSuccessAction, url, $t) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Shieldgate_PaymentGateway/payment/shieldgate_ltp'
            },
            afterPlaceOrder: function () {
                redirectOnSuccessAction.redirectUrl = url.build("redirectlinktopayshieldgate/placeorder/placeorder");
                this.redirectAfterPlaceOrder = true;
                redirectOnSuccessAction.execute();
            }
        });
    }
);
