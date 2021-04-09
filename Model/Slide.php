<?php

namespace Tinik\MoonSlider\Model;

use \Magento\Framework\Model\AbstractModel;
use \Magento\Framework\DataObject\IdentityInterface;
use \Tinik\MoonSlider\Api\Data\SlideInterface;


class Slide extends AbstractModel implements SlideInterface, IdentityInterface
{

    const ENTITY = 'moon_slider_slide';

    const CACHE_TAG = 'moon_slider_slide';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;


    /**
     *
     * @return string[]
     */
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
        return parent::getData(self::SLIDE_ID);
    }

    protected function _construct()
    {
        $this->_init('Tinik\MoonSlider\Model\ResourceModel\Slide');
    }

    public function getStoreId()
    {
        return $this->getData(self::KEY_STORE_ID);
    }

    public function setStoreId($value)
    {
        return $this->setData(self::KEY_STORE_ID, $value);
    }

    public function getTitle()
    {
        return $this->getData(self::KEY_TITLE);
    }

    public function setTitle($value)
    {
        return $this->setData(self::KEY_TITLE, trim($value));
    }

    public function getKeyword()
    {
        return $this->getData(self::KEY_KEYWORD);
    }

    public function setKeyword($value)
    {
        return $this->setData(self::KEY_KEYWORD, trim($value));
    }

    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED  => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    public function getItems(array $filter = [])
    {
        $entityId = $this->getId();
        return $this->_getResource()->getItems($entityId, $filter);
    }
}
