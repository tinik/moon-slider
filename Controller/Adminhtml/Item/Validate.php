<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\RuntimeException;

/**
 *
 * @todo: Need create correct validation before save by meta data
 */
class Validate extends AbstractAction implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute(): Json
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        $result = new DataObject(['error' => false]);

        try {
            $this->validateRequest();
        } catch (\Exception $err) {
            $result->setData('error', true);
            $result->setData('messages', [
                $err->getMessage()
            ]);
        }

        $response = $this->resultFactory->create($this->resultFactory::TYPE_JSON);
        $response->setData($result);

        return $response;
    }

    /**
     * @return void
     * @throws RuntimeException
     */
    private function validateRequest(): void
    {
        $params = $this->getRequest()->getParams();

        foreach (['title', 'image', 'mobile'] as $field) {
            if (empty($params[$field])) {
                $message = __('The form is not valid');
                throw new RuntimeException($message, null, 4004);
            }
        }
    }
}
