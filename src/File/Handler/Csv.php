<?php
/**
 * CSV File Handler
 *
 * @author Sanjeev Kumar
 * @license MIT
 */

declare(strict_types=1);

namespace Sanjeev\File\Handler;

class Csv
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $orgData = [];

    /**
     * @var array
     */
    protected $header = [];

    public function __construct($filePath = null)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \Exception("File not found or not readable: " . $filePath);
        }

        $this->filePath = $filePath;
    }

    public function loadData()
    {
        $this->orgData = [];
        if (($handle = fopen($this->filePath, 'r')) !== false) {
            $this->header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $this->orgData[] = array_combine($this->header, $row);
            }
            fclose($handle);
        }
        $this->data = $this->orgData;
        return $this;
    }

    public function getOrgData()
    {
        return $this->orgData;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getData()
    {
        return $this->data;
    }

    public function count()
    {
        return count($this->data);
    }

    public function sortByColumn($column, $direction = 'ASC')
    {
        usort($this->data, function ($a, $b) use ($column, $direction) {
            if (!isset($a[$column]) || !isset($b[$column])) {
                return 0;
            }
            if ($direction === 'ASC') {
                return $a[$column] <=> $b[$column];
            } else {
                return $b[$column] <=> $a[$column];
            }
        });
        return $this;
    }

    public function filter(callable $callback)
    {
        $this->data = array_filter($this->orgData, $callback);
        return $this;
    }

    public function reset()
    {
        $this->data = $this->orgData;
        return $this;
    }

    public function getRecord($index)
    {
        if (isset($this->data[$index])) {
            return $this->data[$index];
        }
        return null;
    }

    public function getRecords($offset, $length = null)
    {
        return array_slice($this->data, $offset, $length);
    }

    public function clearData()
    {
        $this->data = [];
        return $this;
    }

    public function updateRecord($index, array $newData)
    {
        if (isset($this->data[$index])) {
            foreach ($this->header as $headerColumn) {
                if (array_key_exists($headerColumn, $newData)) {
                    $this->data[$index][$headerColumn] = $newData[$headerColumn];
                }
            }
        }
        return $this;
    }

    public function addRecord(array $newData)
    {
        $newRecord = [];
        foreach ($this->header as $headerColumn) {
            if (array_key_exists($headerColumn, $newData)) {
                $newRecord[$headerColumn] = $newData[$headerColumn];
            } else {
                $newRecord[$headerColumn] = '';
            }
        }
        $this->data[] = $newRecord;
        return $this;
    }

    public function removeRecord($index)
    {
        if (isset($this->data[$index])) {
            unset($this->data[$index]);
        }
        return $this;
    }

    public function saveToFile($filePath)
    {
        if (($handle = fopen($filePath, 'w')) !== false) {
            fputcsv($handle, $this->header);
            foreach ($this->data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        } else {
            throw new \Exception("Unable to open file for writing: " . $filePath);
        }
        return $this;
    }
}
