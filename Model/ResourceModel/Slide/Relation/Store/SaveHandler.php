<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide;


class SaveHandler implements ExtensionInterface
{

    protected $storeFields = [
        SlideInterface::KEY_TITLE,
    ];

    /** @var MetadataPool */
    protected $metadataPool;

    /** @var Slide */
    protected $resource;

    /**
     * @param MetadataPool $metadataPool
     * @param Slide $resource
     */
    public function __construct(
        MetadataPool $metadataPool,
        Slide $resource
    )
    {
        $this->metadataPool = $metadataPool;
        $this->resource = $resource;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        $connection = $this->resource->getConnection();

        $table = $this->resource->getTable('moon_slider_slide_store');
        $data = [
            'slide_id' => (int)$entity->getId(),
            'store_id' => (int)$entity->getStoreId(),
            'title' => $entity->getTitle(),
        ];

        $select = $connection->select()->from(['t' => $table])
            ->where('t.slide_id = ?', $data['slide_id'])
            ->where('t.store_id = ?', $data['store_id'])
            ->limit(1);

        $rowSet = $connection->fetchAssoc($select);
        if (!empty($rowSet)) {
            $connection->update($table, [
                'title' => $data['title'],
            ], [
                'slide_id = ?' => $data['slide_id'],
                'store_id = ?' => $data['store_id'],
            ]);
        } else {
            $connection->insert($table, $data);
        }

        return $entity;
    }
}
