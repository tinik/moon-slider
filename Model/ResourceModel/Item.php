<?php

namespace Tinik\MoonSlider\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;


class Item extends AbstractDb
{


    /** @var EntityManager */
    private $entityManager;

    public function __construct(
        Context $context,
        EntityManager $entityManager,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
        $this->entityManager = $entityManager;
    }


    protected function _construct()
    {
        $this->_init('moon_slider_slide_item', 'item_id');
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
            \Tinik\MoonSlider\Model\Item::KEY_STORE_ID => $storeId,
        ]);

        return $result;
    }

    public function lookupDetails($itemId, $storeId = null)
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from(['main_table' => 'moon_slider_slide_item_store']);
        $select->where('item_id = ?', $itemId);
        $select->limit(1);
        if (!empty($storeId)) {
            $select->where('store_id = ?', $storeId);
        }

        return $connection->fetchRow($select);
    }
}
