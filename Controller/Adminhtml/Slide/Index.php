<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create($this->resultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('slides/index/index');

        return $resultRedirect;
    }
}
