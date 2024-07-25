<?php
declare(strict_types=1);

namespace Tinik\MoonSlider\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    /**
     * @var string[]
     */
    protected array $extensions = ['jpg', 'jpeg', 'png'];

    /**
     * @var string
     */
    protected string $basePath = '/';

    /**
     * Construct
     *
     * @param Database $mediaDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Database $mediaDatabase,
        private readonly Filesystem $filesystem,
        private readonly UploaderFactory $uploaderFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Get the directory writer
     *
     * @param string $code
     * @return WriteInterface
     * @throws FileSystemException
     */
    private function getDirectoryWrite(string $code): WriteInterface
    {
        return $this->filesystem->getDirectoryWrite($code);
    }

    /**
     * Get directory
     *
     * @return WriteInterface
     * @throws FileSystemException
     */
    private function getDirectory(): WriteInterface
    {
        return $this->getDirectoryWrite(DirectoryList::PUB);
    }

    /**
     * Get the base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * Retrieve the path
     *
     * @param string $path
     * @param string $imageName
     * @return string
     */
    public function getFilePath(string $path, string $imageName): string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Get current base url by type
     *
     * @param string $type
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl(string $type = UrlInterface::URL_TYPE_MEDIA): string
    {
        return $this->storeManager->getStore()->getBaseUrl($type);
    }

    /**
     * Get uri to file
     *
     * @param string $path
     * @return string
     * @throws NoSuchEntityException
     */
    public function getUri(string $path): string
    {
        $filepath = $this->getFilePath($this->getBasePath(), $path);
        return $this->getBaseUrl(UrlInterface::URL_TYPE_LINK) . ltrim($filepath, '/');
    }

    /**
     * Check existing file
     *
     * @param string $filename
     * @return bool
     * @throws FileSystemException
     */
    public function isExist(string $filename): bool
    {
        $path = $this->getFilePath($this->getBasePath(), $filename);
        $filepath = $this->getDirectory()->getAbsolutePath($path);
        return file_exists($filepath);
    }

    /**
     * Get details about file
     *
     * @param string $filename
     * @param bool $isEdit
     * @return array
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function getDetails(string $filename, bool $isEdit = false): array
    {
        $path = $this->getFilePath($this->getBasePath(), $filename);

        $filepath = $this->getDirectory()->getAbsolutePath($path);
        if (file_exists($filepath)) {
            return [
                'url'  => $isEdit === true ? $path : $this->getUri($filename),
                'path' => $path,
                'type' => mime_content_type($filepath),
                'name' => pathinfo($filename, PATHINFO_BASENAME),
                'size' => filesize($filepath),
            ];
        }

        return [];
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     * @param string $tmpPath
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function save(string $fileId, string $tmpPath): array
    {
        $absPath = $this->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($tmpPath);

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->extensions);
        $uploader->setAllowCreateFolders(true);
        $uploader->setFilenamesCaseSensitivity(true);
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($absPath);
        if (!$result) {
            throw new LocalizedException(
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
                $this->mediaDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        $allowed = ['error', 'name', 'size', 'type', 'url'];
        return array_intersect_key($result, array_flip($allowed));
    }
}
