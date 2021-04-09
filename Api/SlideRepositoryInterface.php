<?php

namespace Tinik\MoonSlider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;


interface SlideRepositoryInterface
{
    public function save(SlideInterface $page);

    public function getById($id, $storeId = null);

    /**
     * @param $value
     * @param null $storeId
     * @return SlideInterface
     */
    public function getByKeyword($value, $storeId = null);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(SlideInterface $page);

    public function deleteById($id);
}
