<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Generic implements ButtonProviderInterface
{
    /**
     * Construct
     *
     * @param Context $context
     */
    public function __construct(
        protected readonly Context $context
    ) {
    }

    /**
     * Get slider id from request data
     *
     * @return int
     */
    public function getSlideId(): int
    {
        return (int)$this->context->getRequest()->getParam('slide_id');
    }

    /**
     * Get store id from request
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->context->getRequest()->getParam('store');
    }

    /**
     * Generate url link by
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [];
    }
}
