<?php
namespace SMTraining\Slider\Controller\Adminhtml\Export;

use Magento\Ui\Controller\Adminhtml\Export\GridToCsv;
use Magento\Backend\App\Action\Context;
use SMTraining\Slider\Model\Export\ConvertToCsv;
use Magento\Framework\App\Response\Http\FileFactory;

class ExportCsv extends GridToCsv{

    /**
     * @var ConvertToCsv
     */
    protected $converter;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @param Context $context
     * @param ConvertToCsv $converter
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        ConvertToCsv $converter,
        FileFactory $fileFactory
    ) {
        parent::__construct($context, $converter, $fileFactory);
        $this->converter = $converter;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Export data provider to CSV
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        return $this->fileFactory->create('export.csv', $this->converter->getCsvFile(), 'var');
    }
}