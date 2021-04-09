<?php

namespace Tinik\MoonSlider\Ui\DataProvider\Modifier\Slide\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;


class Slide implements ModifierInterface
{

    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }
}
