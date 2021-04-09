<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class Reset extends Generic implements ButtonProviderInterface
{

    public function getButtonData()
    {
        return [
            'label'      => __('Reset'),
            'class'      => 'reset',
            'on_click'   => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
