<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;


class Index extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('slides/index/index');

        return $resultRedirect;
    }
}
