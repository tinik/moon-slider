<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item as ResourceItem;

class Item extends AbstractModel implements ItemInterface, IdentityInterface
{

    public const ENTITY = 'moon_slider_item';

    public const CACHE_TAG = 'moon_slider_item';

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(ResourceItem::class);
    }

    /**
     * @inheritdoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return (int)$this->getData(self::ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function getSliderId(): int
    {
        return (int)$this->getData(self::KEY_SLIDER_ID, 0);
    }

    /**
     * @inheritdoc
     */
    public function setSliderId(int $value): ItemInterface
    {
        return $this->setData(self::KEY_SLIDER_ID, $value);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId(): int
    {
        return (int)$this->getData(self::KEY_STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId(int $value): ItemInterface
    {
        return $this->setData(self::KEY_SLIDER_ID, $value);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return (string)$this->getData(self::KEY_TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $value): ItemInterface
    {
        return $this->setData(self::KEY_TITLE, trim($value));
    }

    /**
     * @inheritdoc
     */
    public function getContent(): string
    {
        return (string)$this->getData(self::KEY_CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $value): ItemInterface
    {
        return $this->setData(self::KEY_CONTENT, trim($value));
    }

    /**
     * @inheritdoc
     */
    public function getLink(): string
    {
        return (string)$this->getData(self::KEY_LINK);
    }

    /**
     * @inheritdoc
     */
    public function setLink(string $value): ItemInterface
    {
        return $this->setData(self::KEY_LINK, trim($value));
    }

    /**
     * Get mobile image path
     *
     * @return string
     */
    public function getMobile(): string
    {
        return $this->getData('mobile');
    }

    /**
     * Get desktop image path
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData('image');
    }
}
