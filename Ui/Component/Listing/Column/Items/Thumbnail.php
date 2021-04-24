<?php

namespace Tinik\MoonSlider\Ui\Component\Listing\Column\Items;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Tinik\MoonSlider\Helper\ImageUploader;


class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{

    /** @var \Tinik\MoonSlider\Helper\ImageUploader */
    protected $uploader;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ImageUploader $uploader,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->uploader = $uploader;
    }

    public function prepareDataSource(array $source)
    {
        if (isset($source['data']['items'])) {
            $fieldname = $this->getData('name');

            foreach ($source['data']['items'] as &$row) {
                $uri = $this->uploader->getUri($row[$fieldname]);

                $row[$fieldname . '_alt'] = 'None';
                $row[$fieldname . '_src'] = $uri;
                $row[$fieldname . '_orig_src'] = $uri;
            }
        }

        return $source;
    }
}
