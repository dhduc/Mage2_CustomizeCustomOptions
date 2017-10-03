<?php
/**
 * Created by PhpStorm.
 * User: kienpham
 * Date: 5/5/17
 * Time: 9:57 AM
 */

namespace Smart\CustomOptions\Model\ResourceModel\Product\Option;

/**
 * save Option value to DB
 * @package Smart\CustomOptions\Model\ResourceModel\Product\Option
 */
class Value extends \Magento\Catalog\Model\ResourceModel\Product\Option\Value
{

    /**
     * Value constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        $connectionName = null
    ) {
        parent::__construct($context, $currencyFactory, $storeManager, $config, $connectionName);
    }

    /**
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveValueColor($object);
        //$this->_saveValueImage($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function _saveValueColor(\Magento\Framework\Model\AbstractModel $object)
    {
        $colorTable = $this->getTable('catalog_product_option_type_color');
        $color = $object->getData('color');
        $image = $object->getData('image');
        $displayMode = $object->getData('display_mode');

        if ($displayMode == 'color') {
            $data = $color;
        } else {
            $data = $image;
        }

        $select = $this->getConnection()->select()->from(
            $colorTable,
            'option_type_id'
        )->where(
            'option_type_id = ?',
            (int)$object->getId()
        )->where(
            'store_id = ?',
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $optionTypeId = $this->getConnection()->fetchOne($select);

        if ($optionTypeId) { // update
            $bind = [
                $displayMode => $data,
                'display_mode' => $displayMode
            ];
            $where = [
                'option_type_id = ?' => $optionTypeId,
                'store_id = ?' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ];

            $this->getConnection()->update($colorTable, $bind, $where);
        } else { // insert
            $bind = [
                'option_type_id' => (int)$object->getId(),
                'store_id' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                $displayMode => $data,
                'display_mode' => $displayMode,
            ];
            $this->getConnection()->insert($colorTable, $bind);
        }

//        TODO: some thing
    }
}