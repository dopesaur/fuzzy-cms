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
