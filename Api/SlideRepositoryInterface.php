<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Api\Data\SlideInterface;

interface SlideRepositoryInterface
{
    /**
     * Save object
     *
     * @param SlideInterface $entity
     * @return SlideInterface
     * @throws CouldNotSaveException
     */
    public function save(SlideInterface $entity): SlideInterface;

    /**
     * Get the object by keyword
     *
     * @param int $needle
     * @param int $storeId
     * @return SlideInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $needle, int $storeId = Store::DEFAULT_STORE_ID): SlideInterface;

    /**
     * Get the object by keyword
     *
     * @param string $needle
     * @param int $storeId
     * @return SlideInterface
     * @throws NoSuchEntityException
     */
    public function getByKeyword(string $needle, int $storeId = Store::DEFAULT_STORE_ID): SlideInterface;

    /**
     * Get sliders by criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface;

    /**
     * Delete by object
     *
     * @param SlideInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function delete(SlideInterface $object): bool;

    /**
     * Delete by id
     *
     * @param int $needle
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $needle): bool;
}
