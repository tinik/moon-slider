<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide\CollectionFactory;

class Slide extends AbstractDataProvider
{
    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param PoolInterface $pool
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly RequestInterface $request,
        private readonly PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get the base collection
     *
     * @return void
     */
    private function setQueryCollection(): void
    {
        $this->collection->setPageSize(1);

        $params = $this->request->getParams();
        if (isset($params['store'])) {
            $this->collection->setStoreId($params['store']);
        }
    }

    /**
     * Get data
     *
     * @return array
     * @throws LocalizedException
     */
    public function getData(): array
    {
        $this->setQueryCollection();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }

        return $this->data;
    }

    /**
     * Get meta
     *
     * @return array
     * @throws LocalizedException
     */
    public function getMeta(): array
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
