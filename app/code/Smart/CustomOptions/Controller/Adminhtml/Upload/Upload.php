<?php
/**
 * Created by PhpStorm.
 * User: kienpham
 * Date: 5/16/17
 * Time: 12:03 PM
 */

namespace Smart\CustomOptions\Controller\Adminhtml\Upload;

use Magento\Framework\App\Filesystem\DirectoryList;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\MediaStorage\Model\File\Uploader
     */
    protected $uploader;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Upload constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = array();
        try {
            $this->uploader = $this->_objectManager->create(
                'Magento\MediaStorage\Model\File\Uploader',
                ['fileId' => 'image']
            );

            $this->uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $this->uploader->setAllowRenameFiles(true);
            $this->uploader->setFilesDispersion(true);

            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);

            $result = $this->uploader->save($mediaDirectory->getAbsolutePath('/CustomOptions'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        /** @var \Magento\Framework\Controller\Result\Json $response */
        $response = $this->resultJsonFactory->create();
        $response->setData($result);
        return $response;
    }

}