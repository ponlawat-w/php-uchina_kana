<?php
/**
 * @param $resource
 * @param string[] $header
 * @return array|bool
 */
function fgetcsv_header($resource, $header) {
    $row = fgetcsv($resource);
    if (!$row) {
        return false;
    }
    $newRow = [];
    foreach ($row as $index => $value) {
        $newRow[isset($header[$index]) ? $header[$index] : $index] = $value;
    }
    return $newRow;
}
