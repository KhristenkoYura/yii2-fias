<?php

use yii\db\Schema;
use yii\db\Migration;
use solbianca\fias\console\models\MigrationIndexFias;

/**
 * Class m160711_062538_fias_tables
 */
class m160711_062538_fias_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_bin ENGINE=InnoDB';
        }

        $this->createTable('{{%fias_house}}', [
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('Идентификационный код записи'),
            'house_id' => $this->binary(16)->unsigned()->notNull()->comment('Идентификационный код дома'),
            'address_id' => $this->binary(16)->unsigned()->comment('Идентификационный код адресного объекта'),
            'number' => $this->string()->comment('Номер дома'),
            'building' => $this->string()->comment('Корпус'),
            'structure' => $this->string()->comment('Строение'),
            'cadastral_number' => $this->string(100)->comment('Кадастровый номер'),
            'postal_code' => $this->integer()->unsigned()->comment('Индекс'),
            //'status' => $this->integer()->comment('Статус'),
            'oktmo' => $this->string()->comment('Код по справочнику ОКТМО'),
            'okato' => $this->string()->comment('Код по справочнику ОКАТО'),
            'ifnsul' => $this->integer()->comment('Код ИФНС ЮЛ'),
            'ifnsfl' => $this->integer()->comment('Код ИФНС ФЛ'),
            'updated_at' => $this->datetime()->comment('Дата время внесения (обновления) записи'),
        ], $tableOptions);

        $this->createTable('{{%fias_room}}', [
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('Идентификационный код записи'),
            'room_id' => $this->binary(16)->unsigned()->notNull()->comment('Идентификационный код комнаты'),
            'house_id' => $this->binary(16)->unsigned()->comment('Идентификационный код дома'),
            'number' => $this->string()->comment('Номер квартиры/офиса'),
            'type' => $this->integer()->comment('Тип комнаты'),
            'number_room' => $this->string()->comment('Номер комнаты или помещения'),
            'type_room' => $this->integer()->comment('Тип комнаты'),
            'cadastral_number' => $this->string(100)->comment('Кадастровый номер'),
            'postal_code' => $this->string(6)->comment('Почтовый индекс'),
            'status' => $this->integer()->comment('Статус'),
            'updated_at' => $this->datetime()->comment('Дата время внесения (обновления) записи'),
        ], $tableOptions);


        $this->createTable('{{%fias_stead}}', [
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('Идентификационный код записи'),
            'stead_id' => $this->binary(16)->unsigned()->notNull()->comment('Идентификационный код комнаты'),
            'parent_id' => $this->binary(16)->unsigned()->comment('Идентификационный код адресного объекта'),
            'number' => $this->string()->comment('Номер участка'),
            'cadastral_number' => $this->string(100)->comment('Кадастровый номер'),
            'postal_code' => $this->string(6)->comment('Почтовый индекс'),
            'status' => $this->integer()->comment('Статус'),
            'oktmo' => $this->string()->comment('Код по справочнику ОКТМО'),
            'okato' => $this->string()->comment('Код по справочнику ОКАТО'),
            'ifnsul' => $this->integer()->comment('Код ИФНС ЮЛ'),
            'ifnsfl' => $this->integer()->comment('Код ИФНС ФЛ'),
            'updated_at' => $this->datetime()->comment('Дата время внесения (обновления) записи'),
        ], $tableOptions);


        $this->createTable('{{%fias_address_object}}', [
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('Идентификационный код записи'),
            'address_id' => $this->binary(16)->unsigned()->comment('Идентификационный код адресного объекта'),
            'parent_id' => $this->binary(16)->unsigned()->notNull()->comment('Идентификационный код родительского адресного объекта'),
            'address_level' => $this->integer()->comment('Уровень объекта по ФИАС'),
            'title' => $this->string()->comment('Наименование объекта'),
            'cadastral_number' => $this->string(100)->comment('Кадастровый номер'),
            'postal_code' => $this->integer()->comment('Почтовый индекс'),
            'status' => $this->integer()->comment('Статус'),
            'region' => $this->string()->comment('Регион'),
            'prefix' => $this->string()->comment('Ул., пр. и так далее'),
            'area_code' => $this->string()->comment('Код района'),
            'auto_code' => $this->string()->comment('Код автономии'),
            'city_code' => $this->string()->comment('Код города'),
            'ctar_code' => $this->string()->comment('Код внутригородского района'),
            'place_code' => $this->string()->comment('Код населённого пункта'),
            'street_code' => $this->string()->comment('Код улицы'),
            'extr_code' => $this->string()->comment('Код дополнительного адресообразующего элемента'),
            'sext_code' => $this->string()->comment('Код подчиненного дополнительного адресообразующего элемента'),
            'plain_code' => $this->string()->comment('Код адресного объекта из КЛАДР 4.0 одной строкой без признака актуальности (последних двух '),
            'code' => $this->string()->comment('Код адресного объекта одной строкой с признаком актуальности из КЛАДР 4.0'),
            'okato' => $this->string()->comment('Код по справочнику ОКАТО'),
            'oktmo' => $this->string()->comment('Код по справочнику ОКТМО'),
            'ifnsul' => $this->integer()->comment('Код ИФНС ЮЛ'),
            'ifnsfl' => $this->integer()->comment('Код ИФНС ФЛ'),
            'updated_at' => $this->datetime()->comment('Дата время внесения (обновления) записи'),
        ], $tableOptions);


        $this->createTable('{{%fias_address_object_level}}', [
            'title' => $this->string()->comment('Описание уровня'),
            'code' => $this->string()->comment('Код уровня'),
        ], $tableOptions);

        $this->addPrimaryKey('pk', '{{%fias_address_object_level}}', ['title', 'code']);

        $this->createTable('{{%fias_update_log}}', [
            'id' => $this->primaryKey(),
            'version_id' => $this->integer()->unique()->notNull()->comment('ID версии, полученной от базы ФИАС'),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%fias_region}}', [
            'id' => $this->string()->comment('Номер региона'),
            'title' => $this->string()->comment('Название региона'),
        ], $tableOptions);

        $this->addPrimaryKey('pk', '{{%fias_region}}', 'id');


        (new MigrationIndexFias())->up();
    }

    public function down()
    {
        $this->dropTable('{{%fias_house}}');
        $this->dropTable('{{%fias_room}}');
        $this->dropTable('{{%fias_stead}}');
        $this->dropTable('{{%fias_address_object}}');
        $this->dropTable('{{%fias_address_object_level}}');
        $this->dropTable('{{%fias_update_log}}');
        $this->dropTable('{{%fias_region}}');
    }
}
