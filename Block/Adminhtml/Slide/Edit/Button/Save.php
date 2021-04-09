<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Ui\Component\Control\Container;

/**
 *
 * Class Save
 */
class Save extends Generic
{

    /** @var string */
    private $target = '';

    protected function getTargetName()
    {
        if (empty($this->target)) {
            $this->target = 'moon_slider_slides_new.moon_slider_slides_new';

            $slideId = $this->getSlideId();
            if (!empty($slideId)) {
                $this->target = 'moon_slider_slides_form.moon_slider_slides_form';
            }
        }

        return $this->target;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getButtonData()
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
    protected function getOptions()
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
