<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Model\ResourceModel\Slide\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model\ResourceModel\Slide;

class SaveHandler implements ExtensionInterface
{
    /**
     * Construct
     *
     * @param Slide $resource
     */
    public function __construct(private readonly Slide $resource)
    {
    }

    /**
     * Handle save store values
     *
     * @param SlideInterface $entity
     * @param array $arguments
     * @return SlideInterface
     */
    public function execute($entity, $arguments = [])
    {
        $connection = $this->resource->getConnection();

        $table = $this->resource->getTable('moon_slider_slide_store');
        $data = [
            'slide_id' => $entity->getId(),
            'store_id' => $entity->getStoreId(),
            'title' => $entity->getTitle()
        ];

        $select = $connection->select()
            ->from(['main_table' => $table])
            ->where('slide_id = ?', $data['slide_id'])
            ->where('store_id = ?', $data['store_id'])
            ->limit(1);

        $rowSet = $connection->fetchAssoc($select);
        if (!empty($rowSet)) {
            $connection->update(
                $table,
                ['title' => $data['title']],
                ['slide_id = ?' => $data['slide_id'], 'store_id = ?' => $data['store_id']]
            );
        } else {
            $connection->insert($table, $data);
        }

        return $entity;
    }
}
