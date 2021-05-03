<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;


abstract class AbstractAction extends \Magento\Backend\App\Action
{

    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Tinik_MoonSlider::items';

    const DEFAULT_MESSAGE = 'Parameter is incorrect.';

    /** @var SlideRepositoryInterface */
    protected $slideRepository;

    protected $entity = null;

    public function __construct(Action\Context $context, SlideRepositoryInterface $slideRepository)
    {
        parent::__construct($context);

        $this->slideRepository = $slideRepository;
    }

    protected function getInstance()
    {
        if (!$this->entity) {
            $slide_id = $this->getRequest()->getParam('slide_id');
            if (empty($slide_id)) {
                throw new \Magento\Framework\Exception\NotFoundException(
                    __(self::DEFAULT_MESSAGE)
                );
            }

            $slide = $this->slideRepository->getById($slide_id);
            if (empty($slide) || !$slide) {
                throw new \Magento\Framework\Exception\NotFoundException(
                    __(self::DEFAULT_MESSAGE)
                );
            }

            $this->entity = $slide;
        }

        return $this->entity;
    }

    protected function createException($message, array $params = [])
    {
        return new \Magento\Framework\Exception\NotFoundException(
            __($message, $params)
        );
    }

}
