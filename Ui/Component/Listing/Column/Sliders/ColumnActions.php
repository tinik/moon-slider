<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Sliders;

use Magento\Ui\Component\Listing\Columns\Column;


class ColumnActions extends Column
{

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $context = $this->getContext();
            $path = 'slides/slide';

            $items = &$dataSource['data']['items'];
            foreach ($items as &$row) {
                $name = $this->getData('name');

                $row[$name]['edit'] = [
                    'label' => __('Edit'),
                    'href'  => $context->getUrl("$path/edit", [
                        'slide_id' => $row['slide_id'],
                    ]),
                ];

                $row[$name]['delete'] = [
                    'label'   => __('Delete'),
                    'post'    => true,
                    'href'    => $context->getUrl("$path/delete", [
                        'slide_id' => $row['slide_id'],
                    ]),
                    'confirm' => [
                        'title'   => __('Delete'),
                        'message' => __('Are you sure you want to delete a %1 record?', '')
                    ],
                ];
            }
        }

        return $dataSource;
    }

}
