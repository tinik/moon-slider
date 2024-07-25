<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Item\Relation\Store;

use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Model\ItemRepository;

class DeleteHandler
{
    /**
     * Construct
     *
     * @param ItemRepository $repository
     * @param SearchCriteriaBuilderFactory $searchBuilderFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ItemRepository $repository,
        private readonly SearchCriteriaBuilderFactory $searchBuilderFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Handle delete
     *
     * @param ItemInterface $entity
     * @param array $arguments
     * @return ItemInterface
     */
    public function execute(ItemInterface $entity, array $arguments = []): ItemInterface
    {
        if ($entity->getId()) {
            $searchBuilder = $this->searchBuilderFactory->create();
            $searchBuilder->addFilter(ItemInterface::ITEM_ID, $entity->getId());

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
