<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Sliders;

use Magento\Ui\Component\Listing\Columns\Column;


class Items extends Column
{

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $context = $this->getContext();

            $items = &$dataSource['data']['items'];
            foreach ($items as $key => &$row) {
                $name = $this->getData('name');

                $row[$name]['view'] = [
                    'label' => __('Manage'),
                    'href'  => $context->getUrl('moon_slider_slides/item/index', [
                        'slide_id' => $row['slide_id']
                    ]),
                ];
            }
        }

        return $dataSource;
    }

}
