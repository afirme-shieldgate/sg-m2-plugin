<?php

namespace Shieldgate\PaymentGateway\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\Exception as MagentoValidatorException;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Shieldgate\PaymentGateway\Gateway\Config\CardConfig;
use Shieldgate\PaymentGateway\Gateway\Config\GatewayConfig;
use Shieldgate\PaymentGateway\Helper\Logger;

class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @var Logger
     */
    public $logger;

    /**
     * DataAssignObserver constructor.
     * @param GatewayConfig $config
     */
    public function __construct(GatewayConfig $config)
    {
        $this->logger = $config->logger;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $data = $this->readDataArgument($observer);
        $paymentInfo = $method->getInfoInstance();
        $additional_data = $data->getDataByKey('additional_data');

        switch ($data->getDataByKey('method')) {

            case CardConfig::CODE:
                $installment = isset($additional_data['installment']) ? $additional_data['installment'] : 1;
                $token = isset($additional_data['token']) ? $additional_data['token'] : null;

                $paymentInfo->setAdditionalInformation('installment', $installment);
                $paymentInfo->setAdditionalInformation('token', $token);
                // TODO: Implement more fields as: bin, termination, brand
                $paymentInfo->setAdditionalInformation('bin', '411111');
                $paymentInfo->setAdditionalInformation('termination', '1111');
                $paymentInfo->setAdditionalInformation('brand', 'Visa');
                break;

            //  Add here more payment methods as: LTP, Cash, PSE

        }
        $this->logger->debug(sprintf('DataAssignObserver.execute $paymentInfo:'), (array)$paymentInfo);
    }
}
