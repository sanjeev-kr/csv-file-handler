# Php class to perform functions like filter, sort, add and update records etc. on CSV file.

## How to install via composer
```
composer require sanjeev-kr/csv-file-handler
```
## How to use examples

```
<?php

include 'vendor/autoload.php';

$csvReader = new \Sanjeev\File\Handler\Csv('Path/to/csv/file');
$csvReader->loadData();
// Read all rows
$rows = $csvReader->getData();

// Iterate through rows
foreach ($rows as $row) {
    echo $row['column_name'];
}

// Read specific row
$row = $csvReader->getRecord(10);

// Get headers
$headers = $csvReader->getHeaders();

// Filter rows by applying conditions on multiple columns
$callable = function ($data) {
    return  $data['column_name'] == 'value' && $data['other_column_name'] == 'value1';
};

$csvReader->filter($callable);
$data = $csvReader->getData();
print_r($data);

// Get row count
$count = $csvReader->count();

// Sort by specific column
$csvReader->sortByColumn("column_name", 'ASC');
$data = $csvReader->getData();
print_r($data);

// Add records, columns names must match with columns names of csv
$newRow = [];
$newRow['column_1'] = 'value1';
$newRow['column_2'] = 'value2';
$newRow['column_3'] = 'value3';
$csvReader->addRecord($newRow);
$data = $csvReader->getData();
print_r($data);


// Update record, column name must match with column name of csv
$rowNumber = 3;
$newData = [];
$newData['column_1'] = 'value1';
$newData['column_2'] = 'value2';
$csvReader->updateRecord($rowNumber, $newData);
$data = $csvReader->getData();
print_r($data);
```
