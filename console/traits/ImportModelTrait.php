<?php
namespace solbianca\fias\console\traits;

use solbianca\fias\models\FiasModelInterface;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Console;
use solbianca\fias\console\base\XmlReader;

/**
 * @mixin ActiveRecord
 * @mixin FiasModelInterface
 */
trait ImportModelTrait
{
    protected static $importFile = 'import.csv';

    /**
     * @param XmlReader $reader
     * @param array|null $attributes
     * @throws \yii\db\Exception
     */
    public static function import(XmlReader $reader, $attributes = null)
    {
        if (is_null($attributes)) {
            $attributes = static::getXmlAttributes();
        }
        static::processImportRows($reader, $attributes);
        static::importCallback();
    }


    /**
     * @param XmlReader $reader
     * @param array $attributes
     * @param bool $temporaryTable
     * @throws \yii\db\Exception
     */
    protected static function processImportRows(XmlReader $reader, $attributes)
    {
        $count = 0;
        $tableName = static::tableName();
        $fields = implode(', ', array_values($attributes));
        $pathToFile = Yii::$app->getModule('fias')->directory . DIRECTORY_SEPARATOR . static::$importFile;
        $pathToFile = str_replace('\\', '/', $pathToFile);

        $db = static::getDb();

        while ($data = $reader->getRows()) {
            $rows = [];
            foreach ($data as $row) {
                $lineRow = array_map(function($v) use ($db) {
                    if (is_array($v)) {
                        return $v[0];
                    } else {
                        return "'" . addcslashes(str_replace("'", "''", $v), "\000\n\r\\\032") . "'";
                    }
                    }, array_values($row));
                $rows[] = implode(", ", $lineRow);
            }
            if (!empty($rows)) {
                $valuesRows = '(' . implode("),\n(", $rows) . ')';

                $query = "INSERT IGNORE INTO {$tableName} ({$fields}) VALUES $valuesRows" ;

                $count += $db
                    ->createCommand($query)
                    ->execute();
                Console::output("Inserted {$count} rows");
            }
        }
    }

    protected static function saveInFile($filename, $data)
    {
        return file_put_contents($filename, $data);
    }

    /**
     * After import callback
     */
    public static function importCallback()
    {
    }
}