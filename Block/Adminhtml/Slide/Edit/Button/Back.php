<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;


class Back extends Generic
{

    public function getButtonData()
    {
        $href = $this->getUrl('slides/index/index');
        return [
            'label'      => __('Back'),
            'on_click'   => sprintf("location.href = '%s';", $href),
            'class'      => 'back',
            'sort_order' => 10
        ];
    }
}
