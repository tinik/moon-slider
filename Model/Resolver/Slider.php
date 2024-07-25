<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Helper\ImageUploader;
use Tinik\MoonSlider\Model\Item;

class Slider implements ResolverInterface
{
    /**
     * Construct
     *
     * @param SlideRepositoryInterface $slideRepository
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        private readonly SlideRepositoryInterface $slideRepository,
        private readonly ImageUploader $imageUploader
    ) {
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['keyword'])) {
            throw new GraphQlNoSuchEntityException(
                __('No such entity')
            );
        }

        $storeId = $context->getExtensionAttributes()->getStore()->getId();
        $entity = $this->slideRepository->getByKeyword($args['keyword'], (int)$storeId);
        if ($entity->getIsActive() != SlideInterface::STATUS_ENABLED) {
            throw new GraphQlNoSuchEntityException(
                __('Slide is active')
            );
        }

        $result = $entity->toArray();
        $result['model'] = $entity;
        $result['items'] = [];

        $items = $entity->getItems(['is_active' => Item::STATUS_ENABLED]);
        if (!empty($items)) {
            $values = [];
            foreach ($items as $row) {
                $values[] = $this->prepareItemResult($row->toArray());
            }

            $result['items'] = $values;
        }

        return $result;
    }

    /**
     * Prepare item values
     *
     * @param array $data
     * @return array
     * @throws NoSuchEntityException
     */
    private function prepareItemResult(array $data): array
    {
        static $images = ['image', 'mobile'];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }

            if (in_array($key, $images, true) && !empty($data[$key])) {
                $data[$key] = $this->imageUploader->getUri($data[$key]);
            }
        }

        return $data;
    }
}
