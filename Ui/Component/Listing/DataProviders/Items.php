<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\DataProviders;


use Magento\Framework\App\RequestInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;


class Items extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;

        $slideId = $this->request->getParam('slide_id');
        $storeId = $this->request->getParam('store_id', 0);

        /** @var \Tinik\MoonSlider\Model\ResourceModel\Item\Collection $collection */
        $collection = $collectionFactory->create();
        $collection->setStoreId($storeId)
            ->addOrder('position', $collection::SORT_ORDER_DESC)
            ->addFieldToFilter('slide_id', [
                'eq' => $slideId
            ]);

        $this->collection = $collection;
    }

}
