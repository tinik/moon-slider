<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;

abstract class AbstractAction extends Action
{
    /** Authorization level of a basic admin session */
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::items';

    protected const DEFAULT_MESSAGE = 'Parameter is incorrect.';

    /**
     * Construct
     *
     * @param Action\Context $context
     * @param SlideRepositoryInterface $slideRepository
     */
    public function __construct(
        Action\Context $context,
        protected readonly SlideRepositoryInterface $slideRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Get current slider by id
     *
     * @return SlideInterface
     * @throws NotFoundException
     * @throws NoSuchEntityException
     */
    protected function getInstance(): SlideInterface
    {
        $slideId = $this->getRequest()->getParam('slide_id');
        if (empty($slideId)) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        return $this->slideRepository->getById($slideId);
    }

    /**
     * Create exception for throw
     *
     * @param string $message
     * @param array $params
     * @return NotFoundException
     */
    protected function createException(string $message, array $params = []): NotFoundException
    {
        return new NotFoundException(
            __($message, $params)
        );
    }
}
