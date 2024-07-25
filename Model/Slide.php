<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Api\Data\SlideInterface;

class Slide extends AbstractModel implements SlideInterface, IdentityInterface
{

    public const ENTITY = 'moon_slider_slide';

    public const CACHE_TAG = 'moon_slider_slide';

    /**
     *
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Slide::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getData(self::SLIDE_ID);
    }

    /**
     * Get StoreId
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->getData(self::KEY_STORE_ID, Store::DEFAULT_STORE_ID);
    }

    /**
     * Assign StoreId value
     *
     * @param int $value
     * @return Slide
     */
    public function setStoreId(int $value = Store::DEFAULT_STORE_ID): Slide
    {
        return $this->setData(self::KEY_STORE_ID, $value);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(self::KEY_TITLE);
    }

    /**
     * Assign title value
     *
     * @param string $value
     * @return Slide
     */
    public function setTitle(string $value): Slide
    {
        return $this->setData(self::KEY_TITLE, trim($value));
    }

    /**
     * Get keyword value
     *
     * @return string
     */
    public function getKeyword(): string
    {
        return (string)$this->getData(self::KEY_KEYWORD);
    }

    /**
     * Assign keyword value
     *
     * @param string $value
     * @return Slide
     */
    public function setKeyword(string $value): Slide
    {
        return $this->setData(self::KEY_KEYWORD, trim($value));
    }

    /**
     * Assign is-active value
     *
     * @param int $value
     * @return Slide
     */
    public function setIsActive(int $value): Slide
    {
        return $this->setData(self::KEY_IS_ACTIVE, $value);
    }

    /**
     * Get is-active value
     *
     * @return int
     */
    public function getIsActive(): int
    {
        return (int)$this->getData(self::KEY_IS_ACTIVE);
    }

    /**
     * Get list available statuses
     *
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return [
            self::STATUS_ENABLED  => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * @param array $filter
     * @return mixed
     * @throws LocalizedException
     */
    public function getItems(array $filter = [])
    {
        $slideId = $this->getId();
        $storeId = $this->getStoreId();
        return $this->_getResource()->getItems($slideId, $storeId, $filter);
    }
}
