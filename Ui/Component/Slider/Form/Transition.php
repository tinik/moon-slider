<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Slider\Form;

use Magento\Framework\Data\OptionSourceInterface;

class Transition implements OptionSourceInterface
{
    /**
     * @var array
     */
    private array $options = [];

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        if (empty($this->options)) {
            $this->options = [];
            foreach (range(100, 10000, 100) as $row) {
                $this->options[] = [
                    'value' => $row,
                    'label' => $row . __(' miliseconds'),
                ];
            }
        }

        return $this->options;
    }
}
