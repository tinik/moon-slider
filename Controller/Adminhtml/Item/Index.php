<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

class Index extends AbstractAction
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        return $this->resultFactory->create($this->resultFactory::TYPE_PAGE);
    }
}
