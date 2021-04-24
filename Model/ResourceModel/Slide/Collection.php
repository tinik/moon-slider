<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Slide;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;


class Collection extends AbstractCollection
{

    /** @var int */
    private $storeId;

    protected function _construct()
    {
        $this->_init('Tinik\MoonSlider\Model\Slide', 'Tinik\MoonSlider\Model\ResourceModel\Slide');
    }

    protected function _renderFiltersBefore()
    {
        $this->addLocale();
        return parent::_renderFiltersBefore();
    }

    public function addFieldToFilter($field, $condition = null)
    {
        $this->addLocale();
        return parent::addFieldToFilter($field, $condition);
    }

    public function addLocale()
    {
        if (true !== $this->getFlag('assign_locale')) {
            $this->joinLocale();
            $this->setFlag('assign_locale', true);
        }

        return $this;
    }

    protected function unsetLocale()
    {
        $select = $this->getSelect();

        // Reset part if already assign
        $partFrom = $select->getPart($select::FROM);
        if (isset($partFrom['main_store'])) {
            unset($partFrom['main_store']);
            $select->setPart($select::FROM, $partFrom);
        }

        $this->setFlag('assign_locale', false);
        return $this;
    }

    public function setStoreId($value)
    {
        $this->storeId = $value;

        return $this->unsetLocale()->addLocale();
    }

    /**
     * preview version
     */
    public function joinLocale()
    {
        $ob = ObjectManager::getInstance();

        /** @var StoreManager $storeManager */
        $storeManager = $ob->get(StoreManager::class);

        $defaultStoreId = $storeManager->getDefaultStoreView()->getId();

        $table = $this->getTable('moon_slider_slide_store');
        $alias = 'main_store';

        $simple = true;
        $needle = join(', ', [$defaultStoreId, Store::DEFAULT_STORE_ID]);
        $onCon = "(main_table.slide_id = $alias.slide_id)";
        $conOn = "$onCon AND ($alias.store_id IN ($needle))";

        if ($this->storeId && $defaultStoreId != $this->storeId) {
            $simple = false;
            $conOn = "$onCon AND (
                $alias.store_id='$this->storeId' OR (
                    $alias.store_id='$defaultStoreId' AND main_table.slide_id NOT IN (
                        SELECT i18n.slide_id FROM $table AS i18n WHERE i18n.store_id = $this->storeId
                    )
                )
            )";
        }

        $select = $this->getSelect();
        $select->joinLeft([$alias => $table], $conOn, [
            'title' => "$alias.title",
            'store_id' => "$alias.store_id"
        ]);

        if ($simple) {
            $select->group('main_table.slide_id');
        }

        $this->addFilterToMap('slide_id', 'main_table.slide_id');

        return $this;
    }
}
