<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide;

class ReadHandler implements ExtensionInterface
{
    /**
     * Construct
     *
     * @param Slide $resource
     */
    public function __construct(private readonly Slide $resource)
    {
    }

    /**
     * Handle entity as read
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        /** @var SlideInterface $entity */
        $slideId = $entity->getId() ?? 0;
        if ($slideId) {
            $store = $arguments[SlideInterface::KEY_STORE_ID] ?? null;

            $details = $this->resource->lookupDetails($slideId, $store) ?? [];
            foreach ($details as $key => $value) {
                $entity->setData($key, $value);
            }
        }

        return $entity;
    }
}
