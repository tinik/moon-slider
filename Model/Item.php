<?php

namespace Tinik\MoonSlider\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Tinik\MoonSlider\Api\Data\ItemInterface;


class Item extends AbstractModel implements ItemInterface, IdentityInterface
{

    const ENTITY = 'moon_slider_item';

    const CACHE_TAG = 'moon_slider_item';

    // Status variant
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init('Tinik\MoonSlider\Model\ResourceModel\Item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ITEM_ID);
    }

    public function getTitle()
    {
        $value = $this->getData(self::KEY_TITLE);
        return trim($value);
    }

    public function setSlideId($value)
    {
        return $this->setData(self::KEY_SLIDER_ID, $value);
    }

    public function getLink()
    {
        $value = $this->getData(self::KEY_LINK);
        return trim($value);
    }
}
