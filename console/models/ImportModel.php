<?php

/**
 * Модель для импорта данных из базы fias в mysql базу
 */

namespace solbianca\fias\console\models;

use Yii;
use solbianca\fias\console\base\XmlReader;
use yii\helpers\Console;
use solbianca\fias\models\FiasAddressObject;
use solbianca\fias\models\FiasAddressObjectLevel;
use solbianca\fias\models\FiasHouse;
use solbianca\fias\models\FiasStead;
use solbianca\fias\models\FiasRoom;

class ImportModel extends BaseModel
{
    /**
     * @throws \Exception
     */
    public function run()
    {
        return $this->import();
    }

    /**
     * Import fias data in base
     *
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function import()
    {
        try {
            //Yii::$app->getDb()->createCommand('SET foreign_key_checks = 0;')->execute();

            $this->dropIndexes();

            $this->importAddressObjectLevel();

            $this->importAddressObject();

            $this->importHouse();

            $this->importRoom();

            $this->importStead();

            $this->addIndexes();

            $this->saveLog();

            //Yii::$app->getDb()->createCommand('SET foreign_key_checks = 1;')->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Import fias address object
     */
    private function importAddressObject()
    {
        Console::output('Импорт адресов обектов');
        FiasAddressObject::import(new XmlReader(
            $this->directory->getAddressObjectFile(),
            FiasAddressObject::XML_OBJECT_KEY,
            array_keys(FiasAddressObject::getXmlAttributes()),
            FiasAddressObject::getXmlFilters(),
            FiasAddressObject::getValuesTypeAttributes()
        ));
    }

    /**
     * Import fias house
     */
    private function importHouse()
    {
        Console::output('Импорт домов');
        FiasHouse::import(new XmlReader(
            $this->directory->getHouseFile(),
            FiasHouse::XML_OBJECT_KEY,
            array_keys(FiasHouse::getXmlAttributes()),
            FiasHouse::getXmlFilters(),
            FiasHouse::getValuesTypeAttributes()
        ));
    }

    /**
     * Import fias house
     */
    private function importStead()
    {
        Console::output('Импорт участков');
        FiasStead::import(new XmlReader(
            $this->directory->getSteadFile(),
            FiasStead::XML_OBJECT_KEY,
            array_keys(FiasStead::getXmlAttributes()),
            FiasStead::getXmlFilters(),
            FiasStead::getValuesTypeAttributes()
        ));
    }

    /**
     * Import fias house
     */
    private function importRoom()
    {
        Console::output('Импорт Квартир');
        FiasRoom::import(new XmlReader(
            $this->directory->getRoomFile(),
            FiasRoom::XML_OBJECT_KEY,
            array_keys(FiasRoom::getXmlAttributes()),
            FiasRoom::getXmlFilters(),
            FiasRoom::getValuesTypeAttributes()
        ));
    }


    /**
     * Import fias address object levels
     */
    private function importAddressObjectLevel()
    {
        Console::output('Импорт типов адресных объектов (условные сокращения и уровни подчинения)');
        FiasAddressObjectLevel::import(
            new XmlReader(
                $this->directory->getAddressObjectLevelFile(),
                FiasAddressObjectLevel::XML_OBJECT_KEY,
                array_keys(FiasAddressObjectLevel::getXmlAttributes()),
                FiasAddressObjectLevel::getXmlFilters(),
                FiasAddressObjectLevel::getValuesTypeAttributes()
            )
        );
    }

    /**
     * Get fias base version
     *
     * @param $directory \solbianca\fias\console\base\Directory
     * @return string
     */
    protected function getVersion($directory)
    {
        return $this->fileInfo->getVersionId();
    }

    /**
     * Сбрсываем индексыдля табоиц даееых фиас
     */
    protected function dropIndexes()
    {
        Console::output('Сбрасываем индексы и ключи.');

        (new MigrationIndexFias())->down();
    }

    /**
     * Устанавливаем индексы для таблиц данных фиас
     */
    protected function addIndexes()
    {
        Console::output('Добавляем к данным индексы и ключи.');

        (new MigrationIndexFias())->up();
    }
}