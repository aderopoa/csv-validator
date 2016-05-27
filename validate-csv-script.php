<?php
require 'Validator.php';

// Ensure script only runs on command line.
if (php_sapi_name() != 'cli') {
    echo "Script only works on shell.";
    die();
}

//Delimiter for CSV. Can be updates
$delimiter=',';

if (!isset($argv[1])) {
    echo "File name required.";
    die();
}

//Grab the filename from the arguments
$filename = $argv[1];

//Set Row Validation Rules Here
//Update to required fields
//todo: Make seting rules dynamic
$rowValidation = [
    0 => ['min' => 0, 'numeric' => ''],
    1 => ['name' => ''],
    2 => ['numeric' => '', 'minCount' => 10, 'maxCount' => 12],
    3 => ['email' => ''],
    4 => ['username' => ''],
];

//Initiate Validator and other params
$validator = new Validator();
$objects = [];
$columnCount = 0;
$header = [];


//Read CSV and Run Rules
if(file_exists($filename) && is_readable($filename)) {
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            $columnCount++;

            // Remove first column as it is used to identify Class Keys
            if ($columnCount == 1) {
                $header = $row;
                continue;
            }

            //Initiate Class
            $object = new stdClass();

            //Loop through the CSV rows, validate against $rowValidation and assign correct values to object
            //Add all errors to $object->errors variable on the object.
            //If validation is not set assign the object value
            foreach ($row as $key => $value) {
                if (isset($rowValidation[$key])) {
                    $result = $validator->validateInput($value, $rowValidation[$key]);

                    if ($result['status'] == 'success') {
                        $object->$header[$key] = $value;
                    } else {
                        $object->errors = $result['error_messages'];
                    }
                } else {
                    $object->$header[$key] = $value;
                }

            }

            //Save the object to arrays of object
            $objects[$columnCount - 1]['object'] = $object;

        }
    }
}

//Echo JSON Array of the objects created.
echo json_encode($objects);