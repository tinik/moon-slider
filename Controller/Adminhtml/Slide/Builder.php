<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Helper\Data as HelperData;
use Tinik\MoonSlider\Model\SlideRepository;

class Builder
{
    /**
     * Construct
     *
     * @param SlideRepository $repository
     * @param LoggerInterface $logger
     * @param HelperData $helper
     */
    public function __construct(
        private readonly SlideRepository $repository,
        private readonly LoggerInterface $logger,
        private readonly HelperData $helper
    ) {
    }

    /**
     * Get instance object
     *
     * @param int $slideId
     * @param int $storeId
     * @return SlideInterface
     */
    private function getSlideObject(int $slideId, int $storeId): SlideInterface
    {
        if ($slideId) {
            try {
                return $this->repository->getById($slideId, $storeId);
            } catch (\Exception $e) {
                //@todo: need sending 404 error
                $this->logger->critical($e);
            }
        }

        return $this->repository->createObject();
    }

    /**
     * Assign details
     *
     * @param RequestInterface $request
     * @return SlideInterface
     */
    public function execute(RequestInterface $request): SlideInterface
    {
        $slideId = (int)$request->getParam('slide_id');
        $storeId = (int)$request->getParam('store');

        $slide = $this->getSlideObject($slideId, $storeId);
        $this->helper->setCurrentSlider($slide);

        return $slide;
    }
}
