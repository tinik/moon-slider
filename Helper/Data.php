<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Helper;

use Magento\Framework\Registry;
use Tinik\MoonSlider\Api\Data\SlideInterface;

class Data
{
    /**
     * Construct
     *
     * @param Registry $registry
     */
    public function __construct(private readonly Registry $registry)
    {
    }

    /**
     * Get current slider
     *
     * @return SlideInterface|null
     */
    public function getCurrentSlider(): ?SlideInterface
    {
        return $this->registry->registry('current_slider');
    }

    /**
     * Assign current slider
     *
     * @param SlideInterface $slide
     * @return void
     */
    public function setCurrentSlider(SlideInterface $slide): void
    {
        $this->registry->register('slider', $slide);
        $this->registry->register('current_slider', $slide);
    }
}
