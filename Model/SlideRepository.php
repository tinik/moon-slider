<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide as ObjectResourceModel;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;

class SlideRepository implements SlideRepositoryInterface
{
    /**
     * Construct
     *
     * @param SlideFactory $objectFactory
     * @param ObjectResourceModel $resource
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly SlideFactory $objectFactory,
        private readonly ObjectResourceModel $resource,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * Get the empty object
     *
     * @return SlideInterface
     */
    public function createObject(): SlideInterface
    {
        return $this->objectFactory->create();
    }

    /**
     * @inheritdoc
     */
    public function save(SlideInterface $object): SlideInterface
    {
        try {
            $this->resource->save($object);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the slider: %1', $e->getMessage())
            );
        }

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $needle): bool
    {
        return $this->delete($this->getById($needle));
    }

    /**
     * @inheritdoc
     */
    public function delete(SlideInterface $object): bool
    {
        try {
            $this->resource->delete($object);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $needle, int $storeId = Store::DEFAULT_STORE_ID): SlideInterface
    {
        $object = $this->createObject();
        $object->setStoreId($storeId);

        $this->resource->load($object, $needle, SlideInterface::SLIDE_ID);
        if (!$object->getId()) {
            throw new NoSuchEntityException(
                __('Object with slide_id "%1" does not exist.', $needle)
            );
        }

        return $object;
    }

    /**
     * Get the object by keyword
     *
     * @param string $needle
     * @param int $storeId
     * @return Slide
     * @throws NoSuchEntityException
     */
    public function getByKeyword(string $needle, int $storeId = Store::DEFAULT_STORE_ID): SlideInterface
    {
        $object = $this->createObject();
        if ($storeId) {
            $object->setStoreId($storeId);
        }

        $this->resource->load($object, $needle, 'keyword');
        if (!$object->getId()) {
            throw new NoSuchEntityException(
                __('Object with keyword "%1" does not exist.', $needle)
            );
        }

        return $object;
    }

    /**
     * Get sliders by criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
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
}
