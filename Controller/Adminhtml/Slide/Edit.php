<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * Construct
     *
     * @param Context $context
     * @param Builder $builder
     */
    public function __construct(
        Context $context,
        private readonly Builder $builder
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->builder->execute($this->getRequest());
        return $this->resultFactory->create($this->resultFactory::TYPE_PAGE);
    }
}
