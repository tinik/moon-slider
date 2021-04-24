<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item;


class ReadHandler implements ExtensionInterface
{

    /** @var MetadataPool */
    protected $metadataPool;

    /** @var Item */
    protected $resource;

    /**
     *
     * @param MetadataPool $metadataPool
     * @param Item $resourcePage
     */
    public function __construct(MetadataPool $metadataPool, Item $resourcePage)
    {
        $this->metadataPool = $metadataPool;
        $this->resource = $resourcePage;
    }

    /**
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        /** @var \Tinik\MoonSlider\Model\Item $entity */
        $slideId = $entity->getId() ?? 0;
        if ($slideId) {
            $store = $arguments[\Tinik\MoonSlider\Model\Item::KEY_STORE_ID] ?? null;

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
