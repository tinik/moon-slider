<?php


namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Helper\Data as HelperData;
use Tinik\MoonSlider\Model\SlideRepository;


class Builder
{

    /** @var SlideRepository */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /** @var HelperData */
    private $helper;

    public function __construct(
        SlideRepository $repository,
        LoggerInterface $logger,
        HelperData $helper
    )
    {
        $this->repository = $repository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     *
     * @param $slideId
     * @param $storeId
     * @return \Tinik\MoonSlider\Model\Slide
     */
    private function getSlideObject($slideId, $storeId)
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
     *
     * @param RequestInterface $request
     * @return SlideInterface
     */
    public function build(RequestInterface $request): SlideInterface
    {
        $slideId = (int)$request->getParam('slide_id');
        $storeId = (int)$request->getParam('store', null);

        $slide = $this->getSlideObject($slideId, $storeId);
        $this->helper->setCurrentSlider($slide);

        return $slide;
    }

}
