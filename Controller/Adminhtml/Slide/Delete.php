<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

class Delete extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * @var \Tinik\MoonSlider\Model\SlideRepository
     */
    protected $objectRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Tinik\MoonSlider\Model\SlideRepository $itemRepository
    )
    {
        parent::__construct($context);

        $this->objectRepository = $itemRepository;
    }

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('slide_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // delete model
                $this->objectRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccessMessage(__('You have deleted the object.'));

                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());

                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['slide_id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can not find an object to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');

    }

}
