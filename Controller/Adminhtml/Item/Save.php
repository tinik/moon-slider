<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Tinik\MoonSlider\Api\ItemRepositoryInterface;
use Tinik\MoonSlider\Api\SlideRepositoryInterface;
use Tinik\MoonSlider\Model as SliderModel;


class Save extends AbstractAction
{

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var ItemRepositoryInterface */
    protected $itemRepository;

    /** @var JsonFactory */
    private $jsonFactory;

    /**
     *
     * @param Action\Context $context
     * @param JsonFactory $jsonFactory
     * @param DataPersistorInterface $dataDataPersistor
     * @param SlideRepositoryInterface $slideRepository
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $jsonFactory,
        DataPersistorInterface $dataDataPersistor,
        SlideRepositoryInterface $slideRepository,
        ItemRepositoryInterface $itemRepository
    )
    {
        parent::__construct($context, $slideRepository);

        $this->jsonFactory = $jsonFactory;
        $this->dataPersistor = $dataDataPersistor;
        $this->itemRepository = $itemRepository;
    }

    /**
     *
     * @return array
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    protected function getParams()
    {
        $values = (array)$this->getRequest()->getPostValue();

        $data = $values;
        $data['slide_id'] = $this->getInstance()->getId();
        if (isset($data['is_active']) && ($data['is_active'] || 'true' == $data['is_active'])) {
            $data['is_active'] = SliderModel\Item::STATUS_ENABLED;
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
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $instance = $this->getInstance();
        if (!$instance) {
            throw $this->createException(self::DEFAULT_MESSAGE);
        }

        $id = $this->getRequest()->getParam('item_id');
        try {
            $entity = $this->getEntity($id);
            $params = $this->getParams();

            $entity->setData(array_map('trim', $params));
            $entity->setSlideId($instance->getId());
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

    protected function createResponse($id, array $params = []): Json
    {
        $values = array_merge($params, [
            'data' => [
                'entity_id' => $id
            ]
        ]);

        $result = $this->jsonFactory->create();
        $result->setData($values);
        return $result;
    }

    /**
     * @param $id
     * @return \Tinik\MoonSlider\Model\Item
     */
    protected function getEntity($id)
    {
        if ($id) {
            return $this->itemRepository->getById($id);
        }

        return $this->itemRepository->createObject();
    }
}
