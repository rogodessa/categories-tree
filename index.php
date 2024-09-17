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

    tree($categories);
}

function tree($categories) {

    $tree = [];

    foreach ($categories as $category) {

        if($category['parent_id'] != 0) {
            if(!isset($tree[$category['parent_id']])) {
                $tree[$category['parent_id']] = [];
            }

            $tree[$category['parent_id']][$category['categories_id']] = $category['categories_id'];
        }
    }

    $tree_copy = $tree;

    if(!empty($tree)) {
        foreach ($tree_copy as $key => $value) {
            foreach($value as $child_id) {
                if(isset($tree_copy[$child_id])) {
                    $tree[$key][$child_id] = $tree_copy[$child_id];
                    unset($tree[$child_id]);
                }
            }
        }
    }

    ksort($tree);

    var_dump($tree);

    return $tree;
}


$time_start = microtime(true);
categories();
$time_end = microtime(true);

print_r($time_end - $time_start);