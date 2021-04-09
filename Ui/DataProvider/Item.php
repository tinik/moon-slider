<?php

namespace Tinik\MoonSlider\Ui\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;
use Tinik\MoonSlider\Helper\ImageUploader;


class Item extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /** @var string */
    protected $baseUrl;

    protected $collection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var array */
    protected $loadedData;

    /** @var RequestInterface */
    protected $request;

    /** @var ImageUploader */
    protected $uploader;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param RequestInterface $request
     * @param ImageUploader $uploader
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        RequestInterface $request,
        ImageUploader $uploader,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        $this->uploader = $uploader;
        $this->baseUrl = $uploader->getBaseUrl(UrlInterface::URL_TYPE_WEB);

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->setItemData($item);
        }

        $data = $this->dataPersistor->get('moon_slider_slide_item');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->setItemData($item);

            $this->dataPersistor->clear('moon_slider_slide_item');
        }

        return $this->loadedData;
    }

    private function setItemData($item)
    {
        $itemId = $item->getId();
        if (empty($this->loadedData[$itemId])) {
            $itemData = $item->getData();
            $this->loadedData[$itemId] = $this->prepareValues($itemData);
        }
    }

    private function prepareValues(array $values)
    {
        foreach(['image', 'mobile'] as $key) {
            $info = [];
            if (!empty($values[$key])) {
                $details = $this->uploader->getDetails($values[$key], true);
                if ($details && !empty($details)) {
                    $info = [$details];
                }
            }

            $values[$key] = $info;
        }

        return $values;
    }
}
