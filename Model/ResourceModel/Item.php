<?php

namespace Tinik\MoonSlider\Model\ResourceModel;


class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('moon_slider_slide_item','item_id');
    }

}
