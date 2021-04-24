<?php

namespace Tinik\MoonSlider\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Tinik\MoonSlider\Model\ResourceModel\Item\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;


class Slide extends AbstractDb
{

    /** @var EntityManager */
    protected $entityManager;

    /** @var MetadataPool */
    protected $metadataPool;

    /** @var ItemCollectionFactory */
    protected $itemCollection;

    public function __construct(
        Context $context,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        ItemCollectionFactory $itemCollection,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);

        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        $this->itemCollection = $itemCollection;
    }

    protected function _construct()
    {
        $this->_init('moon_slider_slide', 'slide_id');
    }

    /**
     *
     * @param string $slideId
     * @param array $filter
     * @return array|DataObject[]
     */
    public function getItems(string $slideId, string $storeId, array $filter = [])
    {
        /** @var Collection $collection */
        $collection = $this->itemCollection->create();
        $collection->setStoreId($storeId);
        $collection->setOrder('position', $collection::SORT_ORDER_DESC);

        if (is_array($filter) && !empty($filter)) {
            foreach ($filter as $key => $value) {
                $collection->addFilter($key, $value);
            }
        }

        $items = $collection->addFilter('slide_id', $slideId);
        if ($items->count()) {
            return $items->getItems();
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    public function load(AbstractModel $object, $value, $field = null)
    {
        $storeId = $object->getStoreId();

        $result = parent::load($object, $value, $field);
        $this->entityManager->load($object, $value, [
            \Tinik\MoonSlider\Model\Slide::KEY_STORE_ID => $storeId,
        ]);

        return $result;
    }

    public function lookupDetails($slideId, $storeId = null)
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from(['main_table' => 'moon_slider_slide_store']);
        $select->where('slide_id = ?', $slideId);
        $select->limit(1);
        if (!empty($storeId)) {
            $select->where('store_id = ?', $storeId);
        }

        return $connection->fetchRow($select);
    }
}
