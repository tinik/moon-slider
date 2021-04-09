<?php

namespace Tinik\MoonSlider\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\Slide;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use Tinik\MoonSlider\Helper\ImageUploader;


class Slider implements ResolverInterface
{

    /** @var ImageUploader */
    protected $imageUploader;

    /** @var SlideRepositoryInterface */
    private $slideRepository;

    public function __construct(
        SlideRepositoryInterface $slideRepository,
        ImageUploader $imageUploader
    )
    {
        $this->slideRepository = $slideRepository;
        $this->imageUploader = $imageUploader;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $storeId = $context->getExtensionAttributes()->getStore()->getId();


        /** @var Slide $entity */
        $entity = $this->slideRepository->getByKeyword($args['keyword'], $storeId);
        if ($entity->getIsActive() != \Tinik\MoonSlider\Model\Slide::STATUS_ENABLED) {
            throw new GraphQlNoSuchEntityException(
                __('Slide is active')
            );
        }

        /** @var \Tinik\MoonSlider\Model\Slide $slide */
        $result = $entity->toArray();
        $result['model'] = $entity;
        $result['items'] = [];

        $items = $entity->getItems([
            'is_active' => \Tinik\MoonSlider\Model\Item::STATUS_ENABLED
        ]);

        if (!empty($items)) {
            $uploader = $this->imageUploader;
            $images = ['image', 'mobile'];

            $values = [];
            foreach ($items as $row) {
                /** @var \Tinik\MoonSlider\Model\Item $row */
                $data = $this->prepareItemResult($row->toArray());

                foreach ($images as $key) {
                    if (!empty($data[$key])) {
                        $data[$key] = $uploader->getUri($data[$key]);
                    } else {
                        $data[$key] = null;
                    }
                }

                $values[] = $data;
            }

            $result['items'] = $values;
        }

        return $result;
    }

    private function prepareItemResult(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        return $data;
    }
}
