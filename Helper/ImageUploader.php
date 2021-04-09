<?php

namespace Tinik\MoonSlider\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;


class ImageUploader
{

    protected $extensions = ['jpg', 'jpeg', 'png'];

    /** @var string */
    protected $basePath = '/';

    /** @var \Magento\MediaStorage\Helper\File\Storage\Database */
    private $coreFileStorageDatabase;

    /** @var \Magento\Framework\Filesystem */
    private $filesystem;

    /** @var \Magento\Framework\Filesystem\Directory\WriteInterface */
    private $directory;

    /** @var \Magento\MediaStorage\Model\File\UploaderFactory */
    private $uploaderFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    private $storeManager;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     *
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->filesystem = $filesystem;
        $this->directory = $this->getDirectoryWrite(DirectoryList::PUB);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;

    }

    protected function getDirectoryWrite($code)
    {
        return $this->filesystem->getDirectoryWrite($code);
    }

    /**
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    public function getTempPath($name)
    {
        return $this->getFilePath($this->getBasePath(), $name);
    }

    public function getBaseUrl($type = UrlInterface::URL_TYPE_MEDIA)
    {
        return $this->storeManager->getStore()->getBaseUrl($type);
    }

    public function getUri($name)
    {
        $filepath = $this->getFilePath($this->getBasePath(), $name);
        return $this->getBaseUrl(UrlInterface::URL_TYPE_LINK) . ltrim($filepath, '/');
    }

    public function isExist($filename)
    {
        $path = $this->getFilePath($this->getBasePath(), $filename);
        $filepath = $this->directory->getAbsolutePath($path);
        return file_exists($filepath);
    }

    public function getDetails($filename, $isEdit = false)
    {
        $path = $this->getFilePath($this->getBasePath(), $filename);

        $filepath = $this->directory->getAbsolutePath($path);
        if (file_exists($filepath)) {
            return [
                'type' => mime_content_type($filepath),
                'name' => pathinfo($filename, PATHINFO_BASENAME),
                'size' => filesize($filepath),
                'url'  => $isEdit === true ? $path : $this->getUri($filename),
                'path' => $path,
            ];
        }

        return [];
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     * @param $tmpPath
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save($fileId, $tmpPath)
    {
        $absPath = $this->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($tmpPath);

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->extensions);
        $uploader->setAllowCreateFolders(true);
        $uploader->setFilenamesCaseSensitivity(true);
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($absPath);
        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $filePath = $this->getFilePath($tmpPath, $result['file']);

        $link = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $filePath;
        $result['url'] = parse_url($link, PHP_URL_PATH);
        $result['name'] = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($tmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        $allowed = ['error', 'name', 'size', 'type', 'url'];
        return array_intersect_key($result, array_flip($allowed));
    }
}
