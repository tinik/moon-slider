<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;

class MassActions extends Action
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * Construct
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter $filter
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        if ($collection->getSize() > 0) {
            $action = $this->getRequest()->getParam('action');

            if ($action == 'delete') {
                return $this->deleteBy($collection);
            } elseif ($action == 'disable') {
                return $this->disableBy($collection);
            } elseif ($action == 'enable') {
                return $this->enableBy($collection);
            }
        }

        return $this->createResponse();
    }

    /**
     * Create response redirect
     *
     * @param string|null $message
     * @return ResultInterface
     */
    protected function createResponse(string $message = null): ResultInterface
    {
        if ($message) {
            // display the success message
            $this->messageManager->addSuccessMessage(
                __($message)
            );
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Delete use collection
     *
     * @param Collection $collection
     * @return ResultInterface
     */
    private function deleteBy(Collection $collection): ResultInterface
    {
        foreach ($collection as $row) {
            $row->delete();
        }

        return $this->createResponse('Slides was deleted');
    }

    /**
     * Disable use collection
     *
     * @param Collection $collection
     * @return ResultInterface
     */
    private function disableBy(Collection $collection): ResultInterface
    {
        foreach ($collection as $item) {
            $item->setIsActive(SlideInterface::STATUS_DISABLED);
            $item->save();
        }

        return $this->createResponse('Slides was disabled');
    }

    /**
     * Activate the use collection
     *
     * @param Collection $collection
     * @return ResultInterface
     */
    private function enableBy(Collection $collection): ResultInterface
    {
        foreach ($collection as $item) {
            $item->setIsActive(SlideInterface::STATUS_ENABLED);
            $item->save();
        }

        return $this->createResponse('Slides was enabled');
    }
}
