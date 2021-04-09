<?php

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\EntityManager\MetadataPool;
use Tinik\MoonSlider\Model\ItemRepository;
use Tinik\MoonSlider\Model\ResourceModel\Slide;
use Magento\Framework\Api\SearchCriteriaInterface;
use Psr\Log\LoggerInterface;


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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        /** @var \Tinik\MoonSlider\Model\Slide $entity */
        if ($entity->getId()) {
            /** @var \Magento\Framework\Api\SearchCriteriaBuilder $searchBuilder */
            $searchBuilder = $this->searchBuilderFactory->create();
            $searchBuilder->addFilter('slide_id', $entity->getId());

            $results = $this->repository->getList($searchBuilder->create());
            if ($results->getTotalCount()) {
                foreach ($results as $row) {
                    try {
                        $this->repository->delete($row);
                    } catch (\Exception $err) {
                        $this->logger->error($err);
                    }
                }
            }
        }

        return $entity;
    }
}
