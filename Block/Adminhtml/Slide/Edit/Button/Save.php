<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Ui\Component\Control\Container;

class Save extends Generic
{
    /**
     * Get target name
     *
     * @return string
     */
    protected function getTargetName(): string
    {
        $target = 'moon_slider_slides_new.moon_slider_slides_new';

        $slideId = $this->getSlideId();
        if (!empty($slideId)) {
            $target = 'moon_slider_slides_form.moon_slider_slides_form';
        }

        return $target;
    }

    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'options' => $this->getOptions(),
            'class' => 'save primary',
            'class_name' => Container::SPLIT_BUTTON,
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [[
                            'targetName' => $this->getTargetName(),
                            'actionName' => 'save',
                            'params' => [true, [
                                'store' => $this->getStoreId()
                            ]]
                        ]]
                    ]
                ]
            ],
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $storeId = $this->getStoreId();
        $target = $this->getTargetName();

        $options[] = [
            'label' => __('Save & New'),
            'id_hard' => 'save_and_new',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [[
                            'targetName' => $target,
                            'actionName' => 'save',
                            'params' => [true, [
                                'store' => $storeId,
                                'back'  => 'new',
                            ]]
                        ]]
                    ]
                ]
            ],
        ];

        $options[] = [
            'label' => __('Save & Edit'),
            'id_hard' => 'save_and_edit',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [[
                            'targetName' => $target,
                            'actionName' => 'save',
                            'params' => [true, [
                                'store' => $storeId,
                                'back'  => 'edit'
                            ]]
                        ]]
                    ]
                ]
            ],
        ];

        $options[] = [
            'label' => __('Save & Close'),
            'id_hard' => 'save_and_close',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [[
                            'targetName' => $target,
                            'actionName' => 'save',
                            'params' => [true, [
                                'store' => $storeId,
                            ]]
                        ]]
                    ]
                ]
            ],
        ];

        return $options;
    }
}
