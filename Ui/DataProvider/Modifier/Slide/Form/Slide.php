<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\DataProvider\Modifier\Slide\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class Slide implements ModifierInterface
{
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
        return $data;
    }
}
