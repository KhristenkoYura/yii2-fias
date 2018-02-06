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
            'uniq_house_id' => ['house_id(32)'],
            'address_id(4)',
            //'cadastral_number',
        ],
        'fias_room' => [
            'uniq_room_id' => ['room_id(32)'],
            'house_id(4)',
            'cadastral_number',
        ],
        'fias_stead' => [
            'uniq_stead_id' => ['stead_id(32)'],
            'stead_id(4)',
            'parent_id(4)',
            'cadastral_number',
        ],
        'fias_address_object' => [
            'uniq_address_id' => ['address_id(32)'],
            'parent_id(4)',
            'title',
            'cadastral_number',
        ],
    ];

    public function indexes() {
        $values = [];
        foreach($this->indexes as $table => $indexes) {
            foreach($indexes as $name => $columns) {
                $name = is_int($name)
                    ? 'idx__' . implode('__', (array) $columns)
                    : 'idx__' . $name;
                $name = str_replace('(', '_', $name);
                $name = str_replace(')', '', $name);

                $values[] = [
                    'name' => $name,
                    'columns' => $columns,
                    'table' => "{{%$table}}",
                    'uniq' => strpos($name, 'idx__uniq') === 0
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
