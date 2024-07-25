<?php

declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Index extends Action
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
