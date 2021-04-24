<?php

declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\RuntimeException;
use Tinik\MoonSlider\Model\SlideRepository;


/**
 *
 * @todo: Need create correct validation before save by meta data
 */
class Validate extends AbstractAction implements HttpPostActionInterface, HttpGetActionInterface
{

    /** @var JsonFactory */
    private $jsonFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Metadata\FormFactory $formFactory
     * @param SlideRepository $slideRepository
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        SlideRepository $slideRepository
    ) {
        parent::__construct($context, $slideRepository);

        $this->jsonFactory = $resultJsonFactory;
    }

    public function execute(): Json
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        $result = new DataObject(['error' => false]);
        try {
            $params = $this->getRequest()->getParams();
            $this->validateResponse($params);
        } catch (\Exception $err) {
            $result->setData('error', true);
            $result->setData('messages', [
                $err->getMessage()
            ]);
        }

        return $this->jsonFactory->create()->setData($result);
    }

    private function validateResponse($params)
    {
        foreach (['title', 'image', 'mobile'] as $field) {
            if (empty($params[$field])) {
                $message = __('The form is not valid');
                throw new RuntimeException($message, null, 4004);
            }
        }
    }
}
