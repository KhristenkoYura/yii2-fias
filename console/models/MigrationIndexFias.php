<?php
namespace solbianca\fias\console\models;

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m160711_062539_fias_tables
 */
class MigrationIndexFias extends Migration
{

    public $tables = ['fias_house', 'fias_room', 'fias_stead', 'fias_address_object'];

    public $indexes = [
        'fias_house' => [
            'address_id',
            'house_id',
            //'cadastral_number',
        ],
        'fias_room' => [
            'house_id',
            'room_id',
            'cadastral_number',
        ],
        'fias_stead' => [
            'stead_id',
            'parent_id',
            'cadastral_number',
        ],
        'fias_address_object' => [
            'parent_id',
            'title',
            'cadastral_number',
        ],
    ];

    public function indexes() {
        $values = [];
        foreach($this->indexes as $table => $indexes) {
            foreach($indexes as $columns) {
                $values[] = [
                    'name' => 'idx__' . implode('__', (array) $columns),
                    'columns' => $columns,
                    'table' => "{{%$table}}"
                ];
            }
        }
        return $values;

    }

    public function up()
    {
        foreach($this->tables as $table) {
            //$this->addPrimaryKey('pk', "{{%$table}}", 'id');
        }

        foreach($this->indexes() as  $index) {
            $this->createIndex($index['name'], $index['table'], $index['columns']);
        }
    }

    public function down()
    {
        foreach($this->tables as $table) {
            //$this->dropPrimaryKey('pk', "{{%$table}}");
        }

        foreach($this->indexes() as  $index) {
            $this->dropIndex($index['name'], $index['table']);
        }
    }
}
