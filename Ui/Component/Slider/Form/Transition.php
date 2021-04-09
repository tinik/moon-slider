<?php

namespace Tinik\MoonSlider\Ui\Component\Slider\Form;

use Magento\Framework\Data\OptionSourceInterface;


class Transition implements OptionSourceInterface
{

    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (true == empty($this->options)) {
            $values = [];
            $delay  = range(100, 10000, 100);
            foreach ($delay as $row) {
                $values[] = [
                    'value' => $row,
                    'label' => $row . __(' miliseconds'),
                ];
            }

            $this->options = $values;
        }

        return $this->options;
    }

}
