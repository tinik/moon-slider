<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;
use Tinik\MoonSlider\Model\SlideRepository;

class MassActions extends AbstractAction
{
    /**
     * Construct
     *
     * @param Context $context
     * @param SlideRepository $slideRepository
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter $filter
    ) {
        parent::__construct($context, $slideRepository);
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
     * Create response
     *
     * @param string $message
     * @return ResultInterface
     */
    protected function createResponse(string $message = '')
    {
        if (!empty($message)) {
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

        return $this->createResponse('Items was deleted');
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
            $item->setIsActive(ItemInterface::STATUS_DISABLED);
            $item->save();
        }

        return $this->createResponse('Items was disabled');
    }

    /**
     * Enable use collection
     *
     * @param Collection $collection
     * @return ResultInterface
     */
    private function enableBy(Collection $collection): ResultInterface
    {
        foreach ($collection as $item) {
            $item->setIsActive(ItemInterface::STATUS_ENABLED);
            $item->save();
        }

        return $this->createResponse('Items was enabled');
    }
}
