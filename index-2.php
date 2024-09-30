<?php

function dbconnect(){
    $connection = new \mysqli('localhost', 'root', '', 'test-php-2');

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}


function categories() {
    $connection = dbconnect();

    $query = "SELECT * FROM categories";

    $result = $connection->query($query);

    $categories = [];

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}


function tree(array $elements, $parent_id = 0) {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parent_id) {
            $children = tree($elements, $element['categories_id']);
            $branch[$element['categories_id']] = !empty($children) ? $children : $element['categories_id'];
        }
    }

    return $branch;
}


$time_start = microtime(true);

var_dump( tree(categories()) );

$time_end = microtime(true);

print_r($time_end - $time_start);