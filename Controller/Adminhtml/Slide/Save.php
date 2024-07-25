<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Api\Data\SlideInterface;
use Tinik\MoonSlider\Model as SliderModel;
use Tinik\MoonSlider\Model\SlideRepository;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /**
     * Construct
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SlideRepository $repository
     * @param Builder $builder
     */
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly SlideRepository $repository,
        private readonly Builder $builder
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $request = $this->getRequest();

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $request->getParams();
        if (!empty($data)) {
            $data = $this->prepareParams($data);

            /** @var \Tinik\MoonSlider\Model\Slide $model */
            $model = $this->builder->execute($request);
            try {
                $model->setData($data);
                $model = $this->repository->save($model);
                if (!$model->getId()) {
                    throw new RuntimeException(__('Not saved'));
                }

                $this->messageManager->addSuccessMessage(__('You saved the slider.'));

                $this->dataPersistor->clear('moon_slider_slide');
                if ($request->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'slide_id' => $model->getId(),
                            'store' => $model->getStoreId(),
                            '_current' => true
                        ]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('moon_slider_slide', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'slide_id' => $model->getId(),
                    'store' => $model->getStoreId(),
                ]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Prepare params
     *
     * @param array $data
     * @return array
     */
    private function prepareParams(array $data): array
    {
        if (empty($data['slide_id'])) {
            unset($data['slide_id']);
        }

        if (isset($data['is_active']) && $data['is_active'] === 'true') {
            $data['is_active'] = SlideInterface::STATUS_ENABLED;
        }

        $storeId = Store::DEFAULT_STORE_ID;
        if (isset($data['store']) && $data['store']) {
            $storeId = $data['store'];
        }

        $data['store_id'] = $storeId;

        return $data;
    }
}
