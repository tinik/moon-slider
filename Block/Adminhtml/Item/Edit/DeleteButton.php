<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Item\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        $objectId = 0;
        if (!$objectId) {
            return [];
        }

        $data = json_encode(['data' => ['item_id' => $objectId]]);
        return [
            'label'      => __('Delete'),
            'class'      => 'delete',
            'on_click'   => sprintf("deleteConfirm('%s', '%s', %s)",
                __('Are you sure you want to do this?'),
                $this->getDeleteUrl(),
                $data
            ),
            'sort_order' => 20,
        ];
    }
}
