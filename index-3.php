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


function tree(array $categories): array
{
    $tree = [];
    $references = [];

    foreach ($categories as &$category) {
        $category['children'] = [];
        $references[$category['categories_id']] = &$category;
    }

    foreach ($categories as &$category) {
        if ($category['parent_id'] == '0') {
            $tree[] = &$category;
        } else {
            if (isset($references[$category['parent_id']])) {
                $references[$category['parent_id']]['children'][] = &$category;
            } else {
                // throw new Exception('Invalid parent id: '.$category['parent_id']);
            }
        }
    }

    return $tree;
}


$time_start = microtime(true);

var_dump( tree(categories()) );

$time_end = microtime(true);

print_r($time_end - $time_start);