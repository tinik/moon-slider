<?php

namespace Tinik\MoonSlider\Ui\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;


class Slide extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /** @var DataPersistorInterface */
    protected $persister;

    /** @var RequestInterface */
    private $request;

    /** @var array */
    protected $loadedData = [];

    /** @var PoolInterface */
    private $pool;

    /**
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Collection $collection
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $persister,
        \Magento\Framework\App\RequestInterface $request,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->persister = $persister;
        $this->request = $request;
        $this->pool = $pool;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    private function setQueryCollection()
    {
        $this->collection->setPageSize(1);

        $params = $this->request->getParams();
        if (isset($params['store'])) {
            $this->collection->setStoreId($params['store']);
        }
    }

    public function getData()
    {
        $this->setQueryCollection();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }

        return $this->data;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();
        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
