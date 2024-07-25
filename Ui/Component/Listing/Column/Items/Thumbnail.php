<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Items;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Tinik\MoonSlider\Helper\ImageUploader;


class Thumbnail extends Column
{
    /**
     * @var ImageUploader
     */
    protected ImageUploader $uploader;

    /**
     * Construct
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ImageUploader $uploader
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ImageUploader $uploader,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->uploader = $uploader;
    }

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $source): array
    {
        if (isset($source['data']['items'])) {
            $name = $this->getData('name');
            foreach ($source['data']['items'] as &$row) {
                $uri = $this->uploader->getUri($row[$name]);

                $row[$name . '_alt'] = 'None';
                $row[$name . '_src'] = $uri;
                $row[$name . '_orig_src'] = $uri;
            }
        }

        return $source;
    }
}
