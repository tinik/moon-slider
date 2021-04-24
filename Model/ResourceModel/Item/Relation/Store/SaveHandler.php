<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item;


class SaveHandler implements ExtensionInterface
{

    protected $storeFields = [
        ItemInterface::KEY_TITLE,
        ItemInterface::KEY_CONTENT,
    ];

    /** @var MetadataPool */
    protected $metadataPool;

    /** @var Item */
    protected $resource;

    /**
     * @param MetadataPool $metadataPool
     * @param Item $resource
     */
    public function __construct(
        MetadataPool $metadataPool,
        Item $resource
    )
    {
        $this->metadataPool = $metadataPool;
        $this->resource = $resource;
    }

    public function getValues($entity)
    {
        /** @var \Tinik\MoonSlider\Model\Item $entity */
        $values = [];
        foreach ($this->storeFields as $field) {
            $values[$field] = $entity->getData($field);
        }

        return $values;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        $connection = $this->resource->getConnection();

        $table = $this->resource->getTable('moon_slider_slide_item_store');
        $refer = [
            ItemInterface::ITEM_ID => (int)$entity->getId(),
            ItemInterface::KEY_STORE_ID => (int)$entity->getStoreId(),
        ];

        $select = $connection->select()->from(['i' => $table])
            ->where('i.item_id = ?', $refer[ItemInterface::ITEM_ID])
            ->where('i.store_id = ?', $refer[ItemInterface::KEY_STORE_ID])
            ->limit(1);

        $values = $this->getValues($entity);

        $rowSet = $connection->fetchAssoc($select);
        if (!empty($rowSet)) {
            $connection->update($table, $values, [
                'item_id = ?' => $refer[ItemInterface::ITEM_ID],
                'store_id = ?' => $refer[ItemInterface::KEY_STORE_ID],
            ]);
        } else {
            $values = array_merge($values, $refer);
            $connection->insert($table, $values);
        }

        return $entity;
    }
}
