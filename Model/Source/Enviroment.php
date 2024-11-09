<?php

declare(strict_types=1);


namespace Lotsofpixels\Cronitor\Model\Source;

class Enviroment implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'production',
                'label' => __('production')
            ),
            array(
                'value' => 'staging',
                'label' => __('staging')
            )
        );
    }
}