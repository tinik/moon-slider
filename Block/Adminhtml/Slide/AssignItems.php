<?php
declare(strict_types = 1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide;

use Magento\Backend\Block\Widget;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class AssignItems extends Widget
{

    /**
     * @var string
     */
    protected $_template = 'Tinik_MoonSlider::slider/edit/assign_items.phtml';

    /**
     * Get block grid
     *
     * @return void
     */
    public function getBlockGrid(): void
    {
//        static $blockGrid;
//        if (!$blockGrid) {
//            $blockGrid = $this->getLayout()->createBlock(
//                \Tinik\MoonSlider\Block\Adminhtml\Slide\Tab\Items::class,
//                'slider.items.grid'
//            );
//        }
//
//        return $blockGrid;
    }
}
