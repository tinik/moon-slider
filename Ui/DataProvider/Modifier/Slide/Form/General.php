<?php

namespace Tinik\MoonSlider\Ui\DataProvider\Modifier\Slide\Form;

use Magento\Framework\Registry;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;


class General implements ModifierInterface
{

    /** @var \Tinik\MoonSlider\Helper\Data */
    private $helper;

    public function __construct(\Tinik\MoonSlider\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $slider = $this->helper->getCurrentSlider();
        if (!empty($slider)) {
            $data[$slider->getId()] = $slider->getData();
        }

        return $data;
    }
}
