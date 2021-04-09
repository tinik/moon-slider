<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Item\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class SaveButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        return [
            'sort_order'     => 90,
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'form-role' => 'save',
                'mage-init' => [
                    'button' => ['event' => 'save']
                ],
            ],
        ];
    }
}
