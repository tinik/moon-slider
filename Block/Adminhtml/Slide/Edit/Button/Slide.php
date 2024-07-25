<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

class Slide extends Generic
{
    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        return [
            'label'      => __('Manage Slides'),
            'class'      => 'slide-action scalable action-secondary',
            'on_click'   => '',
            'sort_order' => 40,
            'data_attribute' => [
                'mage-init' => [
                    'Tinik_MoonSlider/js/manage' => [
                        'slider' => $this->getSlideId(),
                    ]
                ]
            ],
        ];
    }
}
