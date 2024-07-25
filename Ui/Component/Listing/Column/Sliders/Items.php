<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Sliders;

use Magento\Ui\Component\Listing\Columns\Column;

class Items extends Column
{
    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $context = $this->getContext();
            foreach ($dataSource['data']['items'] as &$row) {
                $name = $this->getData('name');

                $row[$name]['view'] = [
                    'label' => __('Manage'),
                    'href'  => $context->getUrl(
                        'moon_slider_slides/item/index',
                        ['slide_id' => $row['slide_id']]
                    ),
                ];
            }
        }

        return $dataSource;
    }

}
