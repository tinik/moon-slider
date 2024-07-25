<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Api\Data;

interface ItemInterface
{
    // Status variant
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    public const ITEM_ID = 'item_id';
    public const KEY_SLIDER_ID = 'slider_id';
    public const KEY_STORE_ID = 'store_id';
    public const KEY_TITLE = 'title';
    public const KEY_CONTENT = 'content';
    public const KEY_LINK = 'link';

    /**
     * Get entity id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get slider id
     *
     * @return int
     */
    public function getSliderId(): int;

    /**
     * Set slider Id
     *
     * @param int $value
     * @return ItemInterface
     */
    public function setSliderId(int $value): ItemInterface;

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set store id
     *
     * @param int $value
     * @return ItemInterface
     */
    public function setStoreId(int $value): ItemInterface;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Set title
     *
     * @param string $value
     * @return ItemInterface
     */
    public function setTitle(string $value): ItemInterface;

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Set content value
     *
     * @param string $value
     * @return ItemInterface
     */
    public function setContent(string $value): ItemInterface;

    /**
     * Get link
     *
     * @return string
     */
    public function getLink(): string;

    /**
     * Set link
     *
     * @param string $value
     * @return ItemInterface
     */
    public function setLink(string $value): ItemInterface;

    /**
     * Get mobile image path
     *
     * @return string
     */
    public function getMobile(): string;

    /**
     * Get desktop image path
     *
     * @return string
     */
    public function getImage(): string;
}
