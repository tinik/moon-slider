<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item;

class SaveHandler implements ExtensionInterface
{
    /**
     * @var array
     */
    private array $storeFields = [
        ItemInterface::KEY_TITLE,
        ItemInterface::KEY_CONTENT,
    ];

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
     * Get store values
     *
     * @param ItemInterface $entity
     * @return array
     */
    public function getValues(ItemInterface $entity): array
    {
        $values = [];
        foreach ($this->storeFields as $field) {
            $values[$field] = $entity->getData($field);
        }

        return $values;
    }

    /**
     * Handle save items
     *
     * @param ItemInterface $entity
     * @param array $arguments
     * @return ItemInterface
     */
    public function execute($entity, $arguments = []): ItemInterface
    {
        $connection = $this->resource->getConnection();

        $table = $connection->getTableName('moon_slider_slide_item_store');

        $refer = [
            ItemInterface::ITEM_ID => (int)$entity->getId(),
            ItemInterface::KEY_STORE_ID => (int)$entity->getStoreId(),
        ];

        $select = $connection->select()
            ->from(['main_table' => $table])
            ->where('item_id = ?', $refer[ItemInterface::ITEM_ID])
            ->where('store_id = ?', $refer[ItemInterface::KEY_STORE_ID])
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
