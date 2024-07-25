<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\DataProviders;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Tinik\MoonSlider\Model\ResourceModel\Item\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;

class Items extends AbstractDataProvider
{
    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $slideId = $request->getParam('slide_id');
        $storeId = $request->getParam('store_id', 0);

        /** @var Collection $collection */
        $collection = $collectionFactory->create();
        $collection->setStoreId($storeId)
            ->addOrder('position', $collection::SORT_ORDER_DESC)
            ->addFieldToFilter('slide_id', ['eq' => $slideId]);

        $this->collection = $collection;
    }
}
