<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        return $this->resultFactory->create($this->resultFactory::TYPE_PAGE);
    }
}
