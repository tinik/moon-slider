<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Api\Data\ItemInterface;

class Item extends AbstractDb
{
    /**
     * Construct
     *
     * @param Context $context
     * @param EntityManager $entityManager
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        private readonly EntityManager $entityManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init('moon_slider_slide_item', 'item_id');
    }

    /**
     * @inheritdoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $result = parent::load($object, $value, $field);

        $this->entityManager->load(
            $object,
            $value,
            [ItemInterface::KEY_STORE_ID => $object->getStoreId()]
        );

        return $result;
    }

    public function lookupDetails($itemId, $storeId = Store::DEFAULT_STORE_ID)
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from(['main_table' => $connection->getTableName('moon_slider_slide_item_store')]);
        $select->where('item_id = ?', $itemId);
        if (!empty($storeId)) {
            $select->where('store_id = ?', $storeId);
        }
        $select->limit(1);
        return $connection->fetchRow($select);
    }
}
