<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
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
     * Handle entity
     *
     * @param object $entity
     * @param array $arguments
     * @return SlideInterface
     */
    public function execute($entity, array $arguments = []): SlideInterface
    {
        /** @var SlideInterface $entity */
        if ($entity->getId()) {
            $searchBuilder = $this->searchBuilderFactory->create();
            $searchBuilder->addFilter('slide_id', $entity->getId());

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
