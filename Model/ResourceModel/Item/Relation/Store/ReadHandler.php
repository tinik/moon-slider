<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item;

class ReadHandler implements ExtensionInterface
{
    /**
     * Construct
     *
     * @param Item $resource
     */
    public function __construct(
        private readonly Item $resource
    ) {
    }

    /**
     * Handle read slider
     *
     * @param SlideInterface $entity
     * @param array $arguments
     * @return SlideInterface
     */
    public function execute($entity, $arguments = []): SlideInterface
    {
        $slideId = $entity->getId() ?? 0;
        if ($slideId) {
            $store = $arguments[ItemInterface::KEY_STORE_ID] ?? null;

            $details = $this->resource->lookupDetails($slideId, $store);
            if ($details) {
                foreach ($details as $key => $value) {
                    $entity->setData($key, $value);
                }
            }
        }

        return $entity;
    }
}
