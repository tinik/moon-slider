<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;
use Tinik\MoonSlider\Model\SlideRepository;


class MassActions extends AbstractAction
{

    /** @var Filter */
    private $filter;

    /** @var CollectionFactory */
    private $collectionFactory;

    /**
     *
     * @param Context $context
     * @param SlideRepository $slideRepository
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context, $slideRepository);

        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    protected function getAction()
    {
        return $this->getRequest()->getParam('action');
    }

    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        if ($collection->getSize() > 0) {
            $action = $this->getAction();
            if ($action == 'delete') {
                return $this->deleteBy($collection);
            }
            else if ($action == 'disable') {
                return $this->disableBy($collection);
            }
            else if ($action == 'enable') {
                return $this->enableBy($collection);
            }
        }

        return $this->createResponse();
    }

    protected function createResponse($message = '')
    {
        if (!empty($message)) {
            // display success message
            $this->messageManager->addSuccessMessage(
                __($message)
            );
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    private function deleteBy(Collection $collection)
    {
        foreach ($collection as $row) {
            $row->delete();
        }

        return $this->createResponse('Items was deleted');
    }

    private function disableBy(Collection $collection)
    {
        foreach ($collection as $item) {
            $item->setIsActive(0);
            $item->save();
        }

        return $this->createResponse('Items was disabled');
    }

    private function enableBy(Collection $collection)
    {
        foreach ($collection as $item) {
            $item->setIsActive(1);
            $item->save();
        }

        return $this->createResponse('Items was enabled');
    }
}
