<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tinik\MoonSlider\Model\SlideRepository;


class Edit extends AbstractAction
{

    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        SlideRepository $slideRepository
    )
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context, $slideRepository);
    }

    public function execute()
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        return $this->pageFactory->create();
    }
}
