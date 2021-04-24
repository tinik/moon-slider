<?php

namespace Tinik\MoonSlider\Block\Widget;

use Magento\Framework\Exception\RuntimeException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Helper\ImageUploader;


class Slider extends Template implements BlockInterface
{

    /** @var LoggerInterface */
    private $logger;

    /** @var SlideRepositoryInterface */
    private $slideRepository;

    /** @var string */
    protected $_template = 'Tinik_MoonSlider::moon-slider/widget/default.phtml';

    /** @var ImageUploader */
    private $imageUploader;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        SlideRepositoryInterface $slideRepository,
        ImageUploader $imageUploader,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->logger = $logger;
        $this->slideRepository = $slideRepository;
        $this->imageUploader = $imageUploader;
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->prepareData();
    }

    protected function prepareData()
    {
        try {
            $keyword = $this->getData('keyword');
            if (empty($keyword)) {
                throw new RuntimeException(
                    __('Keyword is required params')
                );
            }

            $store = $this->_storeManager->getStore();

            /** @var \Tinik\MoonSlider\Model\Slide $entity */
            $entity = $this->slideRepository->getByKeyword($keyword, $store->getId());
            if ($entity->getIsActive() != \Tinik\MoonSlider\Model\Slide::STATUS_ENABLED) {
                throw new RuntimeException(
                    __('Slider %1 - is not active', $keyword)
                );
            }

            if ($entity) {
                $this->assign('entity', $entity);

                $exists = [];
                foreach ($entity->getItems() as $item) {
                    if ($item->getIsActive() != \Tinik\MoonSlider\Model\Item::STATUS_ENABLED) {
                        continue;
                    }

                    /** @var \Tinik\MoonSlider\Model\Item $item */
                    $image = $item->getImage();
                    if ($image && $this->imageUploader->isExist($image)) {
                        $exists[] = $item;
                    } else {
                        // write to logger information about not found image/file
                        $this->logger->warning("MoonSlider - Not found image path $image");
                    }
                }

                $this->assign('slides', $exists);
            }
        } catch (\Exception $e) {
            // write to logger exception message
            $this->logger->error("MoonSlider - ". $e->getMessage());
            $this->logger->debug($e->getTraceAsString());
        }
    }

    public function getImage($item, $type = 'image')
    {
        if ($type === 'mobile' && $item->getMobile()) {
            return $this->imageUploader->getUri($item->getMobile());
        }

        return $this->imageUploader->getUri($item->getImage());
    }

    public function getMageSetting()
    {
        $entity = $this->_viewVars['entity'];
        $params = [
            'type'         => (string) $entity->getSlideType() ?? 'slide',
            'speed'        => (int) $entity->getSpeed(),
            'autoplay'     => (bool) $entity->getAutoplay(),
            'pauseOnHover' => (bool) $entity->getHover(),
            'arrows'       => (bool) $entity->getArrows(),
            'direction'    => (string) $entity->getDirection(),
        ];

        if ($entity->getHeight()) {
            $params['height'] = $entity->getHeight();
        }

        if ($entity->getWidth()) {
            $params['width'] = $entity->getWidth();
        }

        return $params;
    }
}
