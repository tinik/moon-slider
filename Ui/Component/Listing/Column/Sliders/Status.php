<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Sliders;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;

class Status extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * Get visible options
     *
     * @return int[]
     */
    public function getVisibleStatusIds(): array
    {
        return [
            SlideInterface::STATUS_ENABLED
        ];
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     * phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function getOptionArray(): array
    {
        return [
            SlideInterface::STATUS_ENABLED => __('Enabled'),
            SlideInterface::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * Retrieve the option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions(): array
    {
        $result = [];
        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string|null
     */
    public function getOptionText($optionId): ?string
    {
        $options = self::getOptionArray();
        return $options[$optionId] ?? null;
    }
}
