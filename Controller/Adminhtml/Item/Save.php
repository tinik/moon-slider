<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Psr\Log\LoggerInterface;
use Tinik\MoonSlider\Api\Data\ItemInterface;
use Tinik\MoonSlider\Api\ItemRepositoryInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;

class Save extends AbstractAction
{
    /**
     * Construct
     *
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     * @param DataPersistorInterface $dataPersistor
     * @param ItemRepositoryInterface $itemRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly ItemRepositoryInterface $itemRepository,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context, $slideRepository);
    }

    /**
     * Get request params
     *
     * @return array
     * @throws NotFoundException
     */
    protected function getParams(): array
    {
        $data = (array)$this->getRequest()->getPostValue();

        $data['slide_id'] = $this->getInstance()->getId();
        if (isset($data['is_active']) && ($data['is_active'] || 'true' == $data['is_active'])) {
            $data['is_active'] = ItemInterface::STATUS_ENABLED;
        }

        foreach (['mobile', 'image'] as $key) {
            if (!empty($data[$key]) && is_array($data[$key][0])) {
                $data[$key] = $data[$key][0]['url'];
            } else {
                $data[$key] = null;
            }
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        $id = (int) $this->getRequest()->getParam('item_id');
        try {
            $entity = $this->getEntity($id);

            $entity->setData($this->getParams());
            $entity->setSliderId($instance->getId());
            if (empty($id)) {
                $entity->setId(null);
            }

            $this->itemRepository->save($entity);
            $id = $entity->getId();

            $this->dataPersistor->clear('moon_slider_slide');
            return $this->createResponse($id, [
                'error' => false
            ]);
        } catch (LocalizedException $e) {
            $this->logger->critical($e);

            return $this->createResponse($id, [
                'error' => true,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return $this->createResponse($id, [
                'error' => true,
                'message' => __('Something went wrong while saving the data.'),
            ]);
        }
    }

    /**
     * Generate save response
     *
     * @param int $objectId
     * @param array $params
     * @return ResultInterface
     */
    private function createResponse(int $objectId, array $params = []): ResultInterface
    {
        $values = array_merge($params, ['data' => ['entity_id' => $objectId]]);

        $result = $this->resultFactory->create($this->resultFactory::TYPE_JSON);
        $result->setData($values);
        return $result;
    }

    /**
     * Get object
     *
     * @param int|null $objectId
     * @return ItemInterface
     */
    private function getEntity(?int $objectId): ItemInterface
    {
        if ($objectId) {
            return $this->itemRepository->getById($objectId);
        }

        return $this->itemRepository->createObject();
    }
}
