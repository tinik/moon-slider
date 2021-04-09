<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\DataProviders\MoonSlider\Item;


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

        /** @var \Tinik\MoonSlider\Model\ResourceModel\Item\Collection $collection */
        $collection = $collectionFactory->create();
        $collection->addOrder('position', $collection::SORT_ORDER_DESC);
        $collection->addFieldToFilter('slide_id', [
            'eq' => $this->request->getParam('slide_id')
        ]);

//var_dump($request->getParams());
//exit;

        $this->collection = $collection;
    }

}
