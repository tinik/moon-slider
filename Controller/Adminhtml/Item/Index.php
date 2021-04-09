<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tinik\MoonSlider\Model\SlideRepository;

class Index extends AbstractAction
{

    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $pageFactory;

    public function __construct(
        Action\Context $context,
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
