<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

class Back extends Generic
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
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
