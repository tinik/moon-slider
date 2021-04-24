<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Item;


use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;

class Collection extends AbstractCollection
{

    protected $storeId = null;

    protected function _construct()
    {
        $this->_init('Tinik\MoonSlider\Model\Item', 'Tinik\MoonSlider\Model\ResourceModel\Item');
        $this->setFlag('assign_locale', false);
    }

    public function setStoreId($value)
    {
        $this->storeId = $value;

        return $this->unsetLocale()->addLocale();
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

    public function addLocale()
    {
        if (true !== $this->getFlag('assign_locale')) {
            $this->joinLocale();
            $this->setFlag('assign_locale', true);
        }

        return $this;
    }

    /**
     *
     * preview version
     */
    public function joinLocale()
    {
        $ob = ObjectManager::getInstance();

        /** @var StoreManager $storeManager */
        $storeManager = $ob->get(StoreManager::class);

        $defaultStoreId = $storeManager->getDefaultStoreView()->getId();

        $table = $this->getTable('moon_slider_slide_item_store');
        $alias = 'main_store';

        $simple = true;
        $needle = join(', ', [$defaultStoreId, Store::DEFAULT_STORE_ID]);
        $onCon = "(main_table.item_id = $alias.item_id)";
        $conOn = "$onCon AND ($alias.store_id IN ($needle))";

        if ($this->storeId && $defaultStoreId != $this->storeId) {
            $simple = false;
            $conOn = "$onCon AND (
                $alias.store_id='$this->storeId' OR (
                    $alias.store_id='$defaultStoreId' AND main_table.item_id NOT IN (
                        SELECT i18n.item_id FROM $table AS i18n WHERE i18n.store_id = $this->storeId
                    )
                )
            )";
        }

        $select = $this->getSelect();
        $select->joinLeft([$alias => $table], $conOn, [
            'title' => "$alias.title",
            'content' => "$alias.content",
            'store_id' => "$alias.store_id"
        ]);

        if ($simple) {
            $select->group('main_table.item_id');
        }

        $this->addFilterToMap('item_id', 'main_table.item_id');

        return $this;
    }
}
