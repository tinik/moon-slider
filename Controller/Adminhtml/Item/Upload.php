<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\RuntimeException;
use Tinik\MoonSlider\Helper\ImageUploader;

class Upload extends Action
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::items';

    /**
     * Construct
     *
     * @param Context $context
     * @param ImageUploader $uploader
     */
    public function __construct(Context $context, private readonly ImageUploader $uploader)
    {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        try {
            $name = $this->getRequest()->getParam('param_name');
            if (!$name) {
                throw new RuntimeException(__('Not found params name'));
            }

            $result = $this->uploader->save($name, 'moon-slider');
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'code' => $e->getCode()];
        }

        $response = $this->resultFactory->create($this->resultFactory::TYPE_JSON);
        $response->setData($result);
        return $response;
    }
}
