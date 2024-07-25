<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Items;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Tinik\MoonSlider\Model\Item;

class Status extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     * phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function getOptionArray(): array
    {
        return [
            Item::STATUS_ENABLED => __('Enabled'),
            Item::STATUS_DISABLED => __('Disabled')
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
