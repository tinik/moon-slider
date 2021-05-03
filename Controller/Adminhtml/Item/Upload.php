<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\RuntimeException;
use Tinik\MoonSlider\Helper\ImageUploader;


class Upload extends Action
{

    const ADMIN_RESOURCE = 'Tinik_MoonSlider::items';

    /** @var ImageUploader */
    protected $uploader;

    public function __construct(
        Action\Context $context,
        ImageUploader $imageUploader,
        JsonFactory $resultFactory
    )
    {
        parent::__construct($context);

        $this->resultFactory = $resultFactory;
        $this->uploader = $imageUploader;
    }

    public function execute()
    {
        try {
            $name = $this->getRequest()->getParam('param_name');
            if (!$name) {
                throw new RuntimeException(
                    __('Not found params name')
                );
            }

            $result = $this->uploader->save($name, 'moon-slider');
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'code' => $e->getCode()];
        }

        return $this->resultFactory->create()->setData($result);
    }
}
