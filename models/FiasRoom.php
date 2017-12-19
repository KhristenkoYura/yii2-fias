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
class FiasRoom extends ActiveRecord implements FiasModelInterface
{
    CONST XML_OBJECT_KEY = 'Room';

    use ImportModelTrait;
    use UpdateModelTrait;
    use DeleteModelTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fias_room}}';
    }

    /**
     * @return string
     */
    public static function temporaryTableName()
    {
        return 'tmp_fias_room';
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
            [['id', 'house_id'], 'required'],
            [['id', 'room_id', 'house_id'], 'string', 'max' => 36],
            [
                [
                    'number',
                    'postal_code',
                ],
                'string',
                'max' => 255
            ],
            [
                ['house_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FiasHouse::className(),
                'targetAttribute' => ['house_id' => 'house_id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'house_id' => 'House ID',
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
            'ROOMID' => 'id',
            'ROOMGUID' => 'room_id',
            'HOUSEGUID' => 'house_id',
            'FLATNUMBER' => 'number',
            'FLATTYPE' => 'type',
            'ROOMNUMBER' => 'number_room',
            'ROOMTYPEID' => 'type_room',
            'POSTALCODE' => 'postal_code',
            'LIVESTATUS' => 'status',
            'CADNUM' => 'cadastral_number',
            'UPDATEDATE' => 'updated_at',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(FiasAddressObject::className(), ['house_id' => 'house_id']);
    }
}
