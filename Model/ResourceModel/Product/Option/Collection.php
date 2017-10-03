<?php
/**
 * Created by PhpStorm.
 * User: kienpham
 * Date: 5/5/17
 * Time: 3:55 PM
 */

namespace Smart\CustomOptions\Model\ResourceModel\Product\Option;

/**
 * get Data form DB to admin edit form
 * @package Smart\CustomOptions\Model\ResourceModel\Product\Option
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Option\Collection
{
    public function addValuesToResult($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->_storeManager->getStore()->getId();
        }
        $optionIds = [];
        foreach ($this as $option) {
            $optionIds[] = $option->getId();
        }
        if (!empty($optionIds)) {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $values */
            $values = $this->_optionValueCollectionFactory->create();
            $values->addTitleToResult(
                $storeId
            )->addPriceToResult(
                $storeId
            );
            $this->addColorToResult($storeId, $values);
            $values->addOptionToFilter(
                $optionIds
            )->setOrder(
                'sort_order',
                self::SORT_ORDER_ASC
            )->setOrder(
                'title',
                self::SORT_ORDER_ASC
            );

            foreach ($values as $value) {
                $optionId = $value->getOptionId();
                if ($this->getItemById($optionId)) {
                    $this->getItemById($optionId)->addValue($value);
                    $value->setOption($this->getItemById($optionId));
                }
            }
        }

        return $this;
    }

    /**
     * @param $storeId
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $values
     */
    public function addColorToResult($storeId, &$values)
    {
        $optionTypeTable = $values->getTable('catalog_product_option_type_color');
        $colorExpr = $values->getConnection()->getCheckSql(
            'store_value_color.color IS NULL',
            'default_value_color.color',
            'store_value_color.color'
        );
        $imageExpr = $values->getConnection()->getCheckSql(
            'store_value_color.image IS NULL',
            'default_value_color.image',
            'store_value_color.image'
        );
        $displayModeExpr = $values->getConnection()->getCheckSql(
            'store_value_color.display_mode IS NULL',
            'default_value_color.display_mode',
            'store_value_color.display_mode'
        );

        $joinExprDefault = 'default_value_color.option_type_id = main_table.option_type_id AND ' .
            $values->getConnection()->quoteInto(
                'default_value_color.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
        $joinExprStore = 'store_value_color.option_type_id = main_table.option_type_id AND ' .
            $values->getConnection()->quoteInto('store_value_color.store_id = ?', $storeId);
        $values->getSelect()->joinLeft(
            ['default_value_color' => $optionTypeTable],
            $joinExprDefault,
            [
                'default_color' => 'color',
                'default_image' => 'image',
                'default_display_mode' => 'display_mode'
            ]
        )->joinLeft(
            ['store_value_color' => $optionTypeTable],
            $joinExprStore,
            [
                'store_image' => 'image',
                'store_color' => 'color',
                'store_display_mode' => 'display_mode',
                'image' => $imageExpr,
                'color' => $colorExpr,
                'display_mode' => $displayModeExpr,
            ]
        );
    }

}