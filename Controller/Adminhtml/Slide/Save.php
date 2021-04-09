<?php

namespace Tinik\MoonSlider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Store\Model\Store;
use Tinik\MoonSlider\Model as SliderModel;
use Tinik\MoonSlider\Model\SlideRepository;


class Save extends Action implements HttpPostActionInterface
{

    const ADMIN_RESOURCE = 'Tinik_MoonSlider::slides';

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var SlideRepository */
    protected $repository;

    /** @var Builder */
    private $builder;

    /**
     *
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SlideRepository $repository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        SlideRepository $repository,
        \Tinik\MoonSlider\Controller\Adminhtml\Slide\Builder $builder
    )
    {
        parent::__construct($context);

        $this->dataPersistor = $dataPersistor;
        $this->repository = $repository;
        $this->builder = $builder;
    }

    /**
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $request = $this->getRequest();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $request->getParams();
        if (!empty($data)) {
            $data = $this->prepareParams($data);

            /** @var \Tinik\MoonSlider\Model\Slide $model */
            $model = $this->builder->build($request);
            try {
                $model->setData($data);
                $model = $this->repository->save($model);
                if (!$model->getId()) {
                    throw new RuntimeException(
                        __('Not saved')
                    );
                }

                $this->messageManager->addSuccessMessage(
                    __('You saved the slider.')
                );

                $this->dataPersistor->clear('moon_slider_slide');
                if ($request->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [
                        'slide_id' => $model->getId(),
                        'store'    => $model->getStoreId(),
                        '_current' => true
                    ]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('moon_slider_slide', $data);
            return $resultRedirect->setPath('*/*/edit', [
                'slide_id' => $model->getId(),
                'store'    => $model->getStoreId(),
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    private function prepareParams(array $data)
    {
        if (empty($data['slide_id'])) {
            unset($data['slide_id']);
        }

        if (isset($data['is_active']) && $data['is_active'] === 'true') {
            $data['is_active'] = SliderModel\Slide::STATUS_ENABLED;
        }

        $storeId = Store::DEFAULT_STORE_ID;
        if (isset($data['store']) && $data['store']) {
            $storeId = $data['store'];
        }

        $data['store_id'] = $storeId;

        return $data;
    }
}
