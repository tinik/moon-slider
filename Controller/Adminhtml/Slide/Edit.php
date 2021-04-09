<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

class Edit extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var Builder */
    private $builder;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tinik\MoonSlider\Controller\Adminhtml\Slide\Builder $builder
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->builder = $builder;
    }

    public function execute()
    {
        $this->builder->build($this->getRequest());
        return $this->resultPageFactory->create();
    }
}
