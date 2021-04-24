<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Items;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;


class Actions extends Column
{

    const BASE_PATH = 'slides/item';

    /** @var UrlInterface */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlBuilder = $urlBuilder;
    }

    protected function getUrl($action, array $params)
    {
        $route = self::BASE_PATH . "/". $action;
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function prepareDataSource(array $dataSource)
    {
        $storeId = $this->context->getRequestParam('store_id');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['item_id'])) {
                    $name = $this->getData('name');

                    $item[$name]['edit'] = [
                        'href' => '#',
                        'label' => __('Edit'),
                        'hidden' => false,
                        'callback' => [[
                                'provider' => 'moon_slider_slides_form.moon_slider_slides_form.items.item_form.moon_slider_items_form_loader',
                                'target' => 'destroyInserted',
                            ], [
                                'provider' => 'moon_slider_slides_form.moon_slider_slides_form.items.item_form',
                                'target' => 'openModal',
                            ], [
                                'provider' => 'moon_slider_slides_form.moon_slider_slides_form.items.item_form.moon_slider_items_form_loader',
                                'target' => 'render',
                                'params' => [
                                    'store_id' => $item['store_id'] ?? $storeId,
                                    'slide_id' => $item['slide_id'],
                                    'item_id'  => $item['item_id'],
                                ],
                            ]
                        ]
                    ];

                    $item[$name]['delete'] = [
                        'label' => __('Delete'),
                        'isAjax' => true,
                        'href'  => $this->getUrl("delete", [
                            'slide_id' => $item['slide_id'],
                            'item_id'  => $item['item_id'],
                            'store_id' => $item['store_id'] ?? $storeId,
                        ]),
                        'confirm' => [
                            'title'   => __('Delete'),
                            'message' => __('Are you sure you want to delete a %2 (%1) record?', [
                                $item['item_id'],
                                $item['title'],
                            ])
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }

}
