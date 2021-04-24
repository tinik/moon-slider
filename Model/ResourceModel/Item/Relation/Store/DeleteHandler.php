<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Model\Item;
use Tinik\MoonSlider\Model\ItemRepository;


class DeleteHandler
{

    /** @var ItemRepository */
    private $repository;

    /** @var SearchCriteriaBuilderFactory */
    private $searchBuilderFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        ItemRepository $repository,
        SearchCriteriaBuilderFactory $searchBuilderFactory,
        LoggerInterface $logger
    )
    {
        $this->repository = $repository;
        $this->searchBuilderFactory = $searchBuilderFactory;
        $this->logger = $logger;
    }

    /**
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        /** @var Item $entity */
        if ($entity->getId()) {
            /** @var SearchCriteriaBuilder $searchBuilder */
            $searchBuilder = $this->searchBuilderFactory->create();
            $searchBuilder->addFilter(Item::ITEM_ID, $entity->getId());

            $results = $this->repository->getList($searchBuilder->create());
            if ($results->getTotalCount()) {
                foreach ($results as $row) {
                    try {
                        $this->repository->delete($row);
                    } catch (Exception $err) {
                        $this->logger->error($err);
                    }
                }
            }
        }

        return $entity;
    }
}
