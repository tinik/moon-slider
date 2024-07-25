<?php
declare(strict_types = 1);

namespace Tinik\MoonSlider\Block\Widget;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Helper\ImageUploader;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Slider extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Tinik_MoonSlider::moon-slider/widget/default.phtml';

    /**
     * Construct
     *
     * @param Context $context
     * @param LoggerInterface $logger
     * @param SlideRepositoryInterface $slideRepository
     * @param ImageUploader $imageUploader
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly LoggerInterface $logger,
        private readonly SlideRepositoryInterface $slideRepository,
        private readonly ImageUploader $imageUploader,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Before rendering html
     *
     * @return void
     */
    protected function _beforeToHtml(): void
    {
        parent::_beforeToHtml();

        $this->prepareData();
    }

    /**
     * Prepare data
     *
     * @return void
     */
    protected function prepareData(): void
    {
        try {
            $keyword = $this->getData('keyword');
            if (empty($keyword)) {
                throw new RuntimeException(
                    __('Keyword is required params')
                );
            }

            $store = $this->_storeManager->getStore();

            $entity = $this->slideRepository->getByKeyword($keyword, (int)$store->getId());
            if ($entity->getIsActive() != SlideInterface::STATUS_ENABLED) {
                throw new RuntimeException(
                    __('Slider %1 - is not active', $keyword)
                );
            }

            $this->assign('entity', $entity);

            $exists = [];
            foreach ($entity->getItems() as $item) {
                if ($item->getIsActive() != ItemInterface::STATUS_ENABLED) {
                    continue;
                }

                $image = $item->getImage();

//                var_dump($image);
//                exit;

                if ($image && $this->imageUploader->isExist($image)) {
                    $exists[] = $item;
                } else {
                    // write to logger information about not found image/file
                    $this->logger->warning('MoonSlider - Not found image path $image');
                }
            }

            $this->assign('slides', $exists);
        } catch (\Exception $e) {
            // write to the logger exception message
            $this->logger->error('MoonSlider - '. $e->getMessage());
            $this->logger->debug($e->getTraceAsString());
        }
    }

    /**
     * Get image uri
     *
     * @param ItemInterface $item
     * @param string $type
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImage(ItemInterface $item, string $type = 'image'): string
    {
        if ($type === 'mobile' && $item->getMobile()) {
            return $this->imageUploader->getUri($item->getMobile());
        }

        return $this->imageUploader->getUri($item->getImage());
    }

    /**
     * Get setting
     *
     * @return array
     */
    public function getMageSetting(): array
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
