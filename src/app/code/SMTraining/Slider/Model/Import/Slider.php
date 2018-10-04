<?php

namespace SMTraining\Slider\Model\Import;

use \Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use SMTraining\Slider\Model\Import\Slider\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use \Magento\ImportExport\Model\Import as ModelImport;

class Slider extends AbstractEntity
{
    const ID = 'slider_id';
    const NAME = 'name';
    const STORE_VIEW = 'store_view';
    const ENABLE = 'enable';

    const TABLE_Entity = 'sm_cms_slider';
    const RELATED_TABLE = 'sm_cms_slider_store';
    const STORE_ID = 'store_id';
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'ID is empty',
    ];
    protected $_permanentAttributes = [self::ID];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    protected $groupFactory;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [self::ID, self::NAME, self::STORE_VIEW, self::ENABLE];
    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory
    )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'smtraining_slider';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        // BEHAVIOR_DELETE use specific validation logic
        // if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
        if (!isset($rowData[self::ID]) || empty($rowData[self::ID])) {
            $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
            return false;
        }
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (ModelImport::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (ModelImport::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (ModelImport::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }
        return true;
    }

    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Deletes newsletter subscriber data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowTitle = $rowData[self::ID];
                    $listTitle[] = $rowTitle;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listTitle) {
            $this->deleteEntityFinish(array_unique($listTitle), self::TABLE_Entity);
            $this->deleteEntityFinish(array_unique($listTitle), self::RELATED_TABLE);
        }
        return $this;
    }

    /**
     * Save and replace newsletter subscriber
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            $relatedEntityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowTitle = $rowData[self::ID];
                $listTitle[] = $rowTitle;

                $entityList[$rowTitle][] = [
                    self::ID => $rowData[self::ID],
                    self::NAME => $rowData[self::NAME],
                    self::ENABLE => $rowData[self::ENABLE],
                ];

                $storeIds = explode(',', $rowData[self::STORE_VIEW]);
                foreach ($storeIds as $storeId) {
                    $relatedEntityList[$rowTitle][] = [
                        self::ID => $rowData[self::ID],
                        self::STORE_ID => $storeId
                    ];
                }
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listTitle) {
                    if ($this->deleteEntityFinish(array_unique($listTitle), self::TABLE_Entity)) {
                        $this->saveEntityFinish($entityList, self::TABLE_Entity);
                    }


                    if ($this->deleteEntityFinish(array_unique($listTitle), self::RELATED_TABLE)) {
                        $this->saveEntityFinish($relatedEntityList, self::RELATED_TABLE);
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_Entity);
                $this->saveEntityFinish($relatedEntityList, self::RELATED_TABLE);
            }
        }
        return $this;
    }

/**
 * Save product prices.
 *
 * @param array $entityData
 * @param string $table
 * @return $this
 */
protected
function saveEntityFinish(array $entityData, $table)
{
    if ($entityData) {
        $tableName = $this->_connection->getTableName($table);
        $entityIn = [];
        foreach ($entityData as $id => $entityRows) {
            foreach ($entityRows as $row) {
                $entityIn[] = $row;
            }
        }
        if ($entityIn) {
            if($table == self::TABLE_Entity){
                $this->_connection->insertOnDuplicate($tableName, $entityIn, [
                    self::ID,
                    self::NAME,
                    self::ENABLE
                ]);
            }
            //if($table == self::RELATED_TABLE)
            else{
                $this->_connection->insertOnDuplicate($tableName, $entityIn, [
                    self::ID,
                    self::STORE_ID
                ]);
            }
        }
    }
    return $this;
}

/**
 * @param array $listTitle
 * @param $table
 * @return bool
 */
protected
function deleteEntityFinish(array $listTitle, $table)
{
    if ($table && $listTitle) {
        try {
            $this->countItemsDeleted += $this->_connection->delete(
                $this->_connection->getTableName($table),
                $this->_connection->quoteInto(self::ID . ' IN (?)', $listTitle)
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    } else {
        return false;
    }
}
}