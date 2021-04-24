<?php

namespace Tinik\MoonSlider\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Store\Model\StoreManagerInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide as ObjectResourceModel;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;
use Tinik\MoonSlider\Model\SlideFactory;


class SlideRepository implements SlideRepositoryInterface
{

    private static $instance = [];

    /** @var \Tinik\MoonSlider\Model\SlideFactory */
    protected $objectFactory;

    /** @var ObjectResourceModel */
    protected $resource;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var SearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    private $storeManager;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    public function __construct(
        SlideFactory $objectFactory,
        ObjectResourceModel $objectResourceModel,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor
    )
    {
        $this->objectFactory = $objectFactory;
        $this->resource = $objectResourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     *
     * @return \Tinik\MoonSlider\Model\Slide
     */
    public function createObject()
    {
        return $this->objectFactory->create();
    }

    public function save(SlideInterface $object)
    {
        try {
            $this->resource->save($object);
            self::$instance = [];
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the slider: %1', $e->getMessage())
            );
        }

        return $object;
    }

    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    public function delete(SlideInterface $object)
    {
        try {
            $this->resource->delete($object);
            self::$instance = [];
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function entityExists($value, $storeId)
    {
        $key = sprintf('%s_%s', $value, $storeId);
        return !empty(self::$instance[$key]);
    }

    public function entityRetrieve($value, $storeId)
    {
        $key = sprintf('%s_%s', $value, $storeId);
        return self::$instance[$key];
    }

    public function entityKeeping(SlideInterface $entity)
    {
        $storeId = $entity->getStoreId();

        $key1 = sprintf('%s_%s', $entity->getId(), $storeId);
        self::$instance[$key1] = $entity;

        $key2 = sprintf('%s_%s', $entity->getKeyword(), $storeId);
        self::$instance[$key2] = $entity;

        return $entity;
    }

    /**
     *
     * @param $value
     * @param null $storeId
     * @return \Tinik\MoonSlider\Model\Slide
     * @throws NoSuchEntityException
     */
    public function getById($value, $storeId = null)
    {
        if ($this->entityExists($value, $storeId)) {
            return $this->entityRetrieve($value, $storeId);
        }

        $object = $this->createObject();
        $object->setStoreId($storeId);

        $this->resource->load($object, $value, SlideInterface::SLIDE_ID);
        if (!$object->getId()) {
            throw new NoSuchEntityException(
                __('Object with slide_id "%1" does not exist.', $value)
            );
        }

        $this->entityKeeping($object);
        return $object;
    }


    /**
     *
     * @param $value
     * @param null $storeId
     * @return \Tinik\MoonSlider\Model\Slide
     * @throws NoSuchEntityException
     */
    public function getByKeyword($value, $storeId = null)
    {
        if ($this->entityExists($value, $storeId)) {
            return $this->entityRetrieve($value, $storeId);
        }

        $object = $this->createObject();
        $object->setStoreId($storeId);

        $this->resource->load($object, $value, 'keyword');
        if (!$object->getId()) {
            throw new NoSuchEntityException(
                __('Object with keyword "%1" does not exist.', $value)
            );
        }

        $this->entityKeeping($object);
        return $object;
    }

    /**
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $items = $collection->getItems();

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setSearchCriteria($criteria);

        return $searchResults;
    }

    public function changeStatusBy(string $operation, array $ids)
    {
        $sliderId = array_filter($ids, 'is_numeric');
        $where = ["id IN (?)" => $sliderId];

        if ($sliderId && 'enable' == $operation) {
            return $this->updateMultiple(['is_active' => 1], $where);
        }

        if ($sliderId && 'disable' == $operation) {
            return $this->updateMultiple(['is_active' => 0], $where);
        }

        throw new RuntimeException(
            __("Available enable/disable action only")
        );
    }

    private function updateMultiple($bind, $where)
    {
        $mainTable = $this->resource->getMainTable();
        return $this->resource->getConnection()->update($mainTable, $bind, $where);
    }
}
