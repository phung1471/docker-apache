<?php
namespace SMTraining\Slider\Model\Import\Slider;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_INVALID_TITLE= 'InvalidValue TITLE';
    const ERROR_TITLE_IS_EMPTY = 'Empty TITLE';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}
