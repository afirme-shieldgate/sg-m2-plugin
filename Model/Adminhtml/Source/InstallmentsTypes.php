<?php

namespace Shieldgate\PaymentGateway\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class InstallmentsTypes implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value'=> 2, 'label'=> __('Deferred with interest')],
            ['value'=> 3, 'label'=> __('Deferred without interest')],
            ['value'=> 9, 'label'=> __('Deferred without interest and months of grace')],
        ];
    }
}
