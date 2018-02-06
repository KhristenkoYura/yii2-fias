<?php

namespace solbianca\fias\models;

use solbianca\fias\console\traits\DeleteModelTrait;
use solbianca\fias\console\traits\UpdateModelTrait;
use yii\db\ActiveRecord;
use solbianca\fias\console\traits\ImportModelTrait;

/**
 * This is the model class for table "{{%fias_house}}".
 *
 * @property string $id
 * @property string $house_id
 * @property string $address_id
 * @property string $number
 * @property string $building
 * @property string $structure
 * @property string $postal_code
 * @property string $okato
 * @property string $oktmo
 * @property string $ifnsul
 * @property string $ifnsfl
 *
 * @property FiasAddressObject $address
 */
class FiasStead extends ActiveRecord implements FiasModelInterface
{
    CONST XML_OBJECT_KEY = 'Stead';

    use ImportModelTrait;
    use UpdateModelTrait;
    use DeleteModelTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fias_stead}}';
    }

    /**
     * @return string
     */
    public static function temporaryTableName()
    {
        return 'tmp_fias_stead';
    }

    /**
     * @return array
     */
    public static function getXmlFilters()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stead_id'], 'required'],
            [['id', 'stead_id', 'parent_id'], 'string', 'max' => 36],
            [
                [
                    'number',
                    'postal_code',
                    'okato',
                    'oktmo',
                    'ifnsul',
                    'ifnsfl',
                ],
                'string',
                'max' => 255
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stead_id' => 'Stead ID',
            'parent_id' => 'Parent ID',
            'number' => 'Number',
            'postal_code' => 'Postal Code',
        ];
    }

    /**
     * @return array
     */
    public static function getXmlAttributes()
    {
        return [
            //'STEADID' => 'id',
            'STEADGUID' => 'stead_id',
            'PARENTGUID' => 'parent_id',
            'NUMBER' => 'number',
            'POSTALCODE' => 'postal_code',
            'OKATO' => 'okato',
            'OKTMO' => 'oktmo',
            'IFNSUL' => 'ifnsul',
            'IFNSFL' => 'ifnsfl',
            'LIVESTATUS' => 'status',
            'CADNUM' => 'cadastral_number',
            'UPDATEDATE' => 'updated_at',
        ];
    }

    public static function getValuesTypeAttributes()
    {
        return [
            'STEADGUID' => 'binary',
            'PARENTGUID' => ['binary', 'null'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(FiasStead::class, ['parent_id' => 'stead_id']);
    }

}
