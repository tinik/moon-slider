<?php

namespace Tinik\MoonSlider\Block\Adminhtml\Item\Edit;

class GenericButton
{

    /** @var \Magento\Backend\Block\Widget\Context */
    protected $context;

    /** @var \Magento\Framework\Registry */
    private $registry;

    /** @var \Magento\Framework\UrlInterface */
    private $urlBuilder;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    )
    {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
        $this->context = $context;
    }

    public function getSlideId()
    {
        return $this->context->getRequest()->getParam('slide_id');
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/', [
            'slide_id' => $this->getSlideId()
        ]);
    }

    public function getDeleteUrl(array $params = [])
    {
        return $this->getUrl('*/*/delete', array_merge($params, [
            'slide_id' => $this->getSlideId(),
        ]));
    }
}
