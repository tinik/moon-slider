<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Api\ItemRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item as ObjectResourceModel;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * Construct
     *
     * @param ItemFactory $objectFactory
     * @param ObjectResourceModel $objectResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        private readonly ItemFactory $objectFactory,
        private readonly ObjectResourceModel $objectResourceModel,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory
    ) {
    }

    /**
     * Get the empty object
     *
     * @return ItemInterface
     */
    public function createObject(): ItemInterface
    {
        return $this->objectFactory->create();
    }

    /**
     * Save object
     *
     * @param ItemInterface $object
     * @return ItemInterface
     * @throws CouldNotSaveException
     */
    public function save(ItemInterface $object): ItemInterface
    {
        try {
            $this->objectResourceModel->save($object);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $object;
    }

    /**
     * Delete by id
     *
     * @param int $value
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $value): bool
    {
        return $this->delete($this->getById($value));
    }

    /**
     * Delete by object
     *
     * @param ItemInterface $object
     * @return true
     * @throws CouldNotDeleteException
     */
    public function delete(ItemInterface $object): bool
    {
        try {
            $this->objectResourceModel->delete($object);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __($exception->getMessage())
            );
        }

        return true;
    }

    /**
     * Get item object
     *
     * @param int $value
     * @return ItemInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $value): ItemInterface
    {
        $object = $this->objectFactory->create();
        $this->objectResourceModel->load($object, $value, ItemInterface::ITEM_ID);
        if (!$object->getId()) {
            throw new NoSuchEntityException(
                __('Object with id "%1" does not exist.', $value)
            );
        }

        return $object;
    }

    /**
     * Get search list items
     *
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }
}
