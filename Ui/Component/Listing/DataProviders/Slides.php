<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\DataProviders;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;

class Slides extends AbstractDataProvider
{
    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
    }
}
