<?php

namespace Tinik\MoonSlider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;


interface ItemRepositoryInterface 
{
    public function save(ItemInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(ItemInterface $page);

    public function deleteById($id);
}
