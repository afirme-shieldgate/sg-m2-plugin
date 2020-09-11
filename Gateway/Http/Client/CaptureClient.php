<?php

namespace Shieldgate\PaymentGateway\Gateway\Http\Client;

use Magento\Sales\Model\Order\Payment;
use Shieldgate\Shieldgate;
use Shieldgate\PaymentGateway\Gateway\Config\CardConfig;
use Shieldgate\PaymentGateway\Gateway\Config\GatewayConfig;
use Shieldgate\PaymentGateway\Model\Adminhtml\Source\Currency;

class CaptureClient extends AbstractClient
{
    /**
     * CaptureClient constructor.
     * @param Shieldgate $adapter
     * @param GatewayConfig $gateway_config
     * @param CardConfig $config
     */
    public function __construct(Shieldgate $adapter, GatewayConfig $gateway_config, CardConfig $config)
    {
        parent::__construct($adapter, $gateway_config);
        $this->config = $config;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    protected function process(array $request_body)
    {
        $is_production = $this->config->isProduction();
        $credentials = $this->config->getServerCredentials();

        $this->adapter->init($credentials['application_code'], $credentials['application_key'], $is_production);

        $charge = $this->adapter::charge();

        /** @var Payment $payment */
        $payment = $request_body['objects']['payment'];
        $order_obj = $request_body['objects']['order'];

        $response = [];

        if (is_null($payment->getParentTransactionId())) {
            $this->logger->debug('CaptureClient.process Authorization is required...');
            $payment->setAdditionalInformation('is_direct_capture', 1);
            $payment->authorize(1, $request_body['order']['amount']);
        }

        if ($payment->getAdditionalInformation('status_detail') == '1') {
            $user = [
                'id' => $request_body['user']['id']
            ];
            $this->logger->debug('CaptureClient.process Use verify for review transactions...');
            $response = $charge->verify('BY_AMOUNT', (string)$request_body['order']['amount'], $payment->getParentTransactionId(), $user, true);
            return (array)$response;
        }
        $transaction_id = !is_null($payment->getParentTransactionId()) ? $payment->getParentTransactionId() : $payment->getTransactionId();

        if (Currency::validateForAuthorize($order_obj->getCurrencyCode())) {
            $this->logger->debug('CaptureClient.process Consuming Capture...');
            $amount = isset($extra_data['additional_amount']) ? $extra_data['additional_amount'] : $request_body['order']['amount'];
            $response = $charge->capture($transaction_id, $amount, true);
        } else {
            $this->logger->debug('CaptureClient.process Use mock for debited transactions...');
            $response = [
                'transaction' => [
                    'id' => $transaction_id,
                    'status' => 'success',
                    'status_detail' => $payment->getAdditionalInformation('status_detail'),
                    'authorization_code' => $payment->getAdditionalInformation('authorization_code'),
                    'message' => $payment->getAdditionalInformation('message'),
                    'carrier_code' => $payment->getAdditionalInformation('carrier_code'),
                ],
            ];
        }

        return (array)$response;
    }
}
