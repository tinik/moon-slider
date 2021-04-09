<?php


namespace Tinik\MoonSlider\Helper;


use Magento\Framework\Registry;

class Data
{

    /** @var Registry */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }


    public function getCurrentSlider()
    {
        return $this->registry->registry('current_slider');
    }

    public function setCurrentSlider($slide)
    {
        $this->registry->register('slider', $slide);
        $this->registry->register('current_slider', $slide);
    }
}
