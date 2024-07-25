<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Slide extends AbstractDb
{
    /**
     * Construct
     *
     * @param Context $context
     * @param EntityManager $entityManager
     * @param ItemCollectionFactory $itemCollection
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        private readonly EntityManager $entityManager,
        private readonly ItemCollectionFactory $itemCollection,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init('moon_slider_slide', 'slide_id');
    }

    /**
     * Get items
     *
     * @param int $slideId
     * @param int $storeId
     * @param array $filter
     * @return array
     */
    public function getItems(int $slideId, int $storeId, array $filter = []): array
    {
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
     * @inheritdoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * Load by field
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string|null $field
     * @return Slide
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $storeId = $object->getStoreId();

        $result = parent::load($object, $value, $field);
        $this->entityManager->load(
            $object,
            $value,
            [SlideInterface::KEY_STORE_ID => $storeId]
        );

        return $result;
    }

    /**
     * Get store details
     *
     * @param int $slideId
     * @param int|null $storeId
     * @return array
     */
    public function lookupDetails(int $slideId, int $storeId = null): array
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from(['main_table' => $connection->getTableName('moon_slider_slide_store')]);
        $select->where('slide_id = ?', $slideId);
        if (!empty($storeId)) {
            $select->where('store_id = ?', $storeId);
        }

        $select->limit(1);
        return $connection->fetchRow($select);
    }
}
