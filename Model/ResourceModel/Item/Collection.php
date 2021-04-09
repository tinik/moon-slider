<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Item;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected function _construct()
    {
        $this->_init('Tinik\MoonSlider\Model\Item','Tinik\MoonSlider\Model\ResourceModel\Item');
    }
}
