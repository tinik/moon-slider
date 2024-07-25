<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Tinik\MoonSlider\Model\SlideRepository;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * Construct
     *
     * @param Context $context
     * @param SlideRepository $objectRepository
     */
    public function __construct(
        Context $context,
        private readonly SlideRepository $objectRepository
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create($this->resultFactory::TYPE_REDIRECT);

        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('slide_id');
        if ($id) {
            try {
                // delete model
                $this->objectRepository->deleteById($id);

                // display a success message
                $this->messageManager->addSuccessMessage(
                    __('You have deleted the object.')
                );

                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());

                // go back to the edit form
                return $resultRedirect->setPath('*/*/edit', ['slide_id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(
            __('We can not find an object to delete.')
        );

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
