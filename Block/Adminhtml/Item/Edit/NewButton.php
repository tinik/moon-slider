<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Item\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class NewButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        $href = $this->getUrl('*/*/new', ['slide_id' => $this->getSlideId()]);

        return [
            'label'      => __('New Item'),
            'class'      => 'primary',
            'sort_order' => 10,
            'on_click'    => sprintf("location.href='%s'", $href)
        ];
    }
}
