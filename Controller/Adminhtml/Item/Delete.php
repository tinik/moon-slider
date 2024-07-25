<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Model\ItemRepository;
use Tinik\MoonSlider\Model\SlideRepository;


class Delete extends AbstractAction
{

    /** @var ItemRepository */
    protected $itemRepository;

    /** @var JsonFactory */
    private $jsonFactory;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param ItemRepository $itemRepository
     * @param SlideRepository $slideRepository
     */
    public function __construct(
        Context $context,
        ItemRepository $itemRepository,
        SlideRepository $slideRepository,
        JsonFactory $jsonFactory,
        LoggerInterface $logger
    )
    {
        parent::__construct($context, $slideRepository);

        $this->itemRepository = $itemRepository;
        $this->jsonFactory = $jsonFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw $this->createException('Type Protocol is not correct');
        }

        try {
            $id = $request->getParam('item_id');

            $instance = $this->getInstance();
            if (!$instance) {
                throw $this->createException(self::DEFAULT_MESSAGE);
            }

            if (!$id) {
                throw $this->createException(self::DEFAULT_MESSAGE);
            }

            // delete model
            $this->itemRepository->deleteById($id);
            return $this->createResponse(true, [
                'error' => false,
                'message' => __('You deleted the item.')
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());

            return $this->createResponse(false, [
                'error' => true,
                'message' => __('We can\'t delete the item right now.')
            ]);
        }
    }

    /**
     * Create result response
     *
     * @param bool $result
     * @param array $values
     * @return ResultInterface
     */
    private function createResponse(bool $result, array $values): ResultInterface
    {
        $values['result'] = $result;

        $response = $this->resultFactory->create($this->resultFactory::TYPE_JSON);
        $response->setData($values);
        return $response;
    }
}
