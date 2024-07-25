<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Helper\ImageUploader;
use Tinik\MoonSlider\Model\ResourceModel\Item\Collection;
use Tinik\MoonSlider\Model\ResourceModel\Item\CollectionFactory;

class Item extends AbstractDataProvider
{
    /**
     * @var array
     */
    private array $loadedData = [];

    /**
     * Constructor
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
        private readonly DataPersistorInterface $dataPersistor,
        private readonly RequestInterface $request,
        private readonly ImageUploader $uploader,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get the base collection
     *
     * @return Collection
     */
    private function getQueryCollection(): Collection
    {
        $this->collection->setPageSize(1);

        $params = $this->request->getParams();
        if ($params && isset($params['store_id'])) {
            $this->collection->setStoreId($params['store_id']);
        }

        return $this->collection;
    }

    /**
     * Get data
     *
     * @return array
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->getQueryCollection()->getItems();
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

    /**
     * Set item data
     *
     * @param ItemInterface $item
     * @return void
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    private function setItemData(ItemInterface $item): void
    {
        $itemId = $item->getId();
        if (empty($this->loadedData[$itemId])) {
            $itemData = $item->getData();
            $this->loadedData[$itemId] = $this->prepareValues($itemData);
        }
    }

    /**
     * Prepare values
     *
     * @param array $values
     * @return array
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    private function prepareValues(array $values): array
    {
        foreach (['image', 'mobile'] as $key) {
            $info = [];
            if (!empty($values[$key])) {
                $details = $this->uploader->getDetails($values[$key], true);
                if ($details) {
                    $info = [$details];
                }
            }

            $values[$key] = $info;
        }

        return $values;
    }
}
