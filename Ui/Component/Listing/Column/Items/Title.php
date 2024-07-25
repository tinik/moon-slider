<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Items;

use Magento\Ui\Component\Listing\Columns\Column;

class Title extends Column
{
    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $source): array
    {
        if (isset($source['data']['items'])) {
            foreach ($source['data']['items'] as &$row) {
                if (empty($row['title'])) {
                    $row['title'] = __('- empty -');
                }
            }
        }

        return $source;
    }
}
