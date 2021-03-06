<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide;


class ReadHandler implements ExtensionInterface
{

    /** @var MetadataPool */
    protected $metadataPool;

    /** @var Slide */
    protected $resource;

    /**
     *
     * @param MetadataPool $metadataPool
     * @param Slide $resourcePage
     */
    public function __construct(MetadataPool $metadataPool, Slide $resourcePage)
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
        /** @var \Tinik\MoonSlider\Model\Slide $entity */
        $slideId = $entity->getId() ?? 0;
        if ($slideId) {
            $store = $arguments[\Tinik\MoonSlider\Model\Slide::KEY_STORE_ID] ?? null;

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
