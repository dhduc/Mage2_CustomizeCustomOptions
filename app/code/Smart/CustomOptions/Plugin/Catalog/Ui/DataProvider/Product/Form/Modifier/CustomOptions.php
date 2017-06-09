<?php
/**
 * Created by PhpStorm.
 * User: kienpham
 * Date: 5/4/17
 * Time: 2:02 PM
 */

namespace Smart\CustomOptions\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Hidden;

/**
 * Them field vao admin custom option form
 * @package Smart\CustomOptions\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier
 */
class CustomOptions
{
    const FIELD_COLOR_NAME = 'color';
    const FIELD_IMAGE_NAME = 'image';
    const FIELD_UPLOAD_NAME = 'upload';
    const FIELD_DISPLAY_MODE_NAME = 'display_mode';

    public function afterModifyMeta(
        \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions $subject,
        $result
    ) {
        $this->addColorOption(
            $result['custom_options']['children']['options']['children']
            ['record']['children']['container_option']['children']
            ['values']['children']['record']['children']
        );

        return $result;
    }


    protected function addColorOption(&$optionValues)
    {
        $optionValues = array_splice($optionValues, 0, 3) + [
                static::FIELD_IMAGE_NAME => $this->getImageFieldConfig(31),
                static::FIELD_UPLOAD_NAME => $this->getUploadFieldConfig(32),
                static::FIELD_COLOR_NAME => $this->getColorFieldConfig(36),
                static::FIELD_DISPLAY_MODE_NAME => $this->getDisplayModeFieldConfig(37)
            ] + $optionValues;
    }

    protected function getImageFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Image'),
                        'componentType' => Field::NAME,
                        'formElement' => Hidden::NAME,
                        'dataScope' => static::FIELD_IMAGE_NAME,
                        'sortOrder' => $sortOrder,
                        'component' => 'Smart_CustomOptions/js/form/element/hidden',
                        'template' => 'Smart_CustomOptions/form/element/hidden'
                    ],
                ],
            ],
        ];
    }


    protected function getUploadFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Upload'),
                        'componentType' => Field::NAME,
                        'formElement' => 'file',
                        'dataScope' => static::FIELD_UPLOAD_NAME,
                        'sortOrder' => $sortOrder,
                        'template' => 'Smart_CustomOptions/form/element/media',
                        'component' => 'Smart_CustomOptions/js/form/element/media'
                    ],
                ],
            ],
        ];
    }

    protected function getColorFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Color'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_COLOR_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'template' => 'Smart_CustomOptions/form/element/color'
                    ],
                ],
            ],
        ];
    }

    protected function getDisplayModeFieldConfig($sortOrder, array $config = [])
    {
        return array_replace_recursive(
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Display Mode'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_DISPLAY_MODE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'options' => $this->toOptionArray(),
                        ],
                    ],
                ],
            ],
            $config
        );
    }

    protected function toOptionArray()
    {
        return [
            ['value' => 'color', 'label' => __('Color')],
            ['value' => 'image', 'label' => __('Image')]
        ];
    }
}