<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Api\Data;

interface SlideInterface
{
    public const SLIDE_ID = 'slide_id';
    public const KEY_TITLE = 'title';
    public const KEY_KEYWORD = 'keyword';
    public const KEY_STORE_ID = 'store_id';
    public const KEY_IS_ACTIVE = 'is_active';

    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**
     * Get Id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get StoreId
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword(): string;

    /**
     * Get is-active
     *
     * @return int
     */
    public function getIsActive(): int;
}
