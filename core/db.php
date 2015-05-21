<?php

/**
 * Connect to database and/or get an instance
 * 
 * @param string $path
 * @return \PDO
 */
function db_connect ($path = '') {
    static $db = null;
    
    if ($db) {
        return $db;
    }
    
    $dsn = "sqlite:$path";
    
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
}

/**
 * Make a database query
 * 
 * @param string $query 
 * @param array $parameters
 * @return \PDOStatement
 */
function db_query ($query, array $parameters = array()) {
    $db = db_connect();
    
    $statement = $db->prepare($query);
    $statement->execute($parameters);
    
    return $statement;
}

/**
 * Select from database
 * 
 * @param string $query 
 * @param array $parameters
 * @param bool $one
 * @return array
 */
function db_select ($query, array $parameters = array(), $one = false) {
    $statement = db_query($query, $parameters);
    
    $result = $one ? $statement->fetch() : $statement->fetchAll();
    
    return $result ? $result : array();
}

/**
 * Browse table
 * 
 * @param string $table
 * @param string $fields
 * @return array
 */
function db_browse ($table, $fields = '*') {
    $query = "SELECT $fields FROM $table ORDER BY id DESC";
    
    return db_select($query);
}

/**
 * Find a row by id
 * 
 * @param string $table
 * @param string $fields
 * @param string|int $id
 * @return array
 */
function db_find ($table, $fields = '*', $id) {
    return db_select("SELECT $fields FROM $table WHERE id = ?", array($id), true);
}

/**
 * Database insert 
 * 
 * @param string $table
 * @param array $data
 * @return int
 */
function db_insert ($table, array $data) {
    if (empty($data)) {
        return 0;
    }
    
    $columns = array_keys($data);
    $columns = array_map(function ($key) {
        return "`$key`";
    }, $columns);
    $columns = implode(',', $columns);
    
    $values = str_repeat('?,', count($data));
    $values = chop($values, ',');
    
    $query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columns, $values);
    
    db_query($query, array_values($data));
    
    return db_connect()->lastInsertId();
}

/**
 * Database update 
 * 
 * @param string $table
 * @param array $data
 * @param string|int $id
 * @return int
 */
function db_update ($table, array $data, $id) {
    if (empty($data)) {
        return 0;
    }
    
    $key_values = array_map(
        function ($key) {
            return "`$key` = ?";
        },
        array_keys($data)
    );
    
    $key_values = implode(', ', $key_values);
    
    $query = sprintf('UPDATE %s SET %s WHERE id = ?', $table, $key_values);
    
    $data   = array_values($data);
    $data[] = $id;
    
    return db_query($query, $data)->rowCount();
}