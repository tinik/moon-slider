<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\DataProviders\MoonSlider\Slider;

use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;


class Slides extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $collection = $collectionFactory->create();
        $this->collection = $collection;
    }
}
