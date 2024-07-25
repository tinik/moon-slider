<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\DataProvider\Modifier\Slide\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Tinik\MoonSlider\Helper\Data;

class General implements ModifierInterface
{
    /**
     * Construct
     *
     * @param Data $helper
     */
    public function __construct(
        private readonly Data $helper
    ) {
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data): array
    {
        $slider = $this->helper->getCurrentSlider();
        if (!empty($slider)) {
            $data[$slider->getId()] = $slider->getData();
        }

        return $data;
    }
}
