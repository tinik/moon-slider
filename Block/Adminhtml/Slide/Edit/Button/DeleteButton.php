<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

class DeleteButton extends Generic
{
    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        if (!$this->getSlideId()) {
            return [];
        }

        $link = $this->getUrl(
            'slides/slide/delete',
            [
                'slide_id' => $this->getSlideId(),
                'store_id' => $this->getStoreId()
            ]
        );

        $message = __('Are you sure you want to do this?');

        return [
            'label'      => __('Delete'),
            'class'      => 'delete',
            'on_click'   => sprintf("deleteConfirm('%s', '%s')", $message, $link),
            'sort_order' => 20,
        ];
    }
}
