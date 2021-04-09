<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Store\Model\Store;

class Generic implements ButtonProviderInterface
{

    /** @var \Magento\Backend\Block\Widget\Context */
    protected $context;

    /** @var string */
    static private $store;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    )
    {
        $this->context = $context;
    }

    public function getSlideId()
    {
        return $this->context->getRequest()
            ->getParam('slide_id');
    }

    public function getStoreId()
    {
        return $this->context->getRequest()
                ->getParam('store');
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    public function getButtonData()
    {
        return [];
    }
}
