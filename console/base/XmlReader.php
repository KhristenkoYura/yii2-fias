<?php

namespace solbianca\fias\console\base;

use solbianca\fias\helpers\FileHelper;
use yii\base\InvalidConfigException;
use ForceUTF8\Encoding;

class XmlReader
{
    /** @var \XMLReader */
    private $reader;
    private $nodeName;
    private $attributes = [];
    private $filters = [];
    private $values = [];

    public function __construct($pathToFile, $nodeName, array $attributes, array $filters = [], array $values = [])
    {
        $this->nodeName = $nodeName;
        $this->attributes = $attributes;
        $this->filters = $filters;
        $this->values = $values;

        $this->initializeReader($pathToFile);
    }

    private function initializeReader($pathToFile)
    {
        FileHelper::ensureIsReadable($pathToFile);

        $this->reader = new \XMLReader();

        $success = $this->reader->open($pathToFile);
        if (!$success) {
            throw new InvalidConfigException('Ошибка открытия XML файла по адресу: ' . $pathToFile);
        }
    }

    public function getRows($maxCount = 10000)
    {
        $this->ensureMaxCountIsValid($maxCount);

        $result = [];
        $count = 0;
        while (($count < $maxCount) && $this->reader->read()) {
            if ($this->checkIsNodeAccepted($this->reader->name)) {
                $result[] = $this->getRowAttributes();
                ++$count;
            }
        }

        return $result;
    }

    private function ensureMaxCountIsValid($maxCount)
    {
        if ($maxCount < 1) {
            throw new \LogicException('Неверное значение максимального количества строк: ' . $maxCount);
        }
    }

    private function checkIsNodeAccepted($node)
    {
        if ($node != $this->nodeName) {
            return false;
        }

        foreach ($this->filters as $filter) {
            $value = $this->reader->getAttribute($filter['field']);

            switch ($filter['type']) {
                case 'in':
                    if ($filter['value'] && !in_array($value, $filter['value'])) {
                        return false;
                    }
                    break;
                case 'nin':
                    if ($filter['value'] && in_array($value, $filter['value'])) {
                        return false;
                    }
                    break;
                case 'hash':
                    if ($filter['value'] && empty($filter['value'][$value])) {
                        return false;
                    }
                    break;
                case 'eq':
                    // no break
                default :
                    if ($filter['value'] != $value) {
                        return false;
                    }
            }
        }

        return true;
    }

    private function getRowAttributes()
    {
        $result = [];
        foreach ($this->attributes as $attribute) {
            // Если атрибут отсутствует, в $result[$attribute] окажется null, также передаем null вместо пустого значения
            $value = $this->reader->getAttribute($attribute) ?: null;
            if ($value !== null && strpos($value, '\\') !== false) {
                $value = preg_replace('%\\\\.?%u', '', $value);
            }

            if (isset($this->values[$attribute])) {
                foreach((array) $this->values[$attribute] as $type) {
                    switch ($type) {
                        case 'int':
                            $chunk = explode('-', $value);
                            $value = hexdec($chunk[0].$chunk[1].$chunk[2]);
                            break;
                        case 'binary':
                            $value = 'X\'' . str_replace('-', '', $value) . '\'';
                            break;
                        case 'null' :
                            if (empty($value)) {
                                $value = '\N';
                            }
                            break;
                    }
                }
            }
            $result[$attribute] = $value;
        }

        return $result;
    }
}
