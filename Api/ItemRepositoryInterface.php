<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;

interface ItemRepositoryInterface
{
    /**
     *
     *
     * @param ItemInterface $page
     * @return ItemInterface
     */
    public function save(ItemInterface $object): ItemInterface;

    /**
     * @param int $value
     * @return ItemInterface
     */
    public function getById(int $value): ItemInterface;

    /**
     *
     *
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface;

    /**
     *
     * @param ItemInterface $page
     * @return bool
     */
    public function delete(ItemInterface $object): bool;

    /**
     * @param int $value
     * @return bool
     */
    public function deleteById(int $value): bool;
}
