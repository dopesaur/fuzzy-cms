<?php

/**
 * Get a key from array if is set
 * 
 * @param array $array
 * @param string $key
 * @param mixed $default
 */
function array_get (array $array, $key, $default = false) {
    if (isset($array[$key])) {
        return $array[$key];
    }
    
    return $default;
}

/**
 * Check whether current user is admin
 *
 * @param bool $authorized
 * @return bool
 */
function is_admin ($authorized = false) {
    static $admin = false;
    
    if ($authorized) {
        $admin = $authorized;
    }
    
    return $admin === true;
}

/**
 * Authorize the user
 * 
 * @param string $username
 * @param string $password
 * @return bool
 */
function auth_user ($username, $password) {
    if (
        $username === 'admin' && 
        $password === md5('123456')
    ) {
        return is_admin(true);
    }
    
    return false;
}

/**
 * Dispatch a route
 * 
 * {$prefix}_{$suffix} (...$parameters)
 * 
 * @param string $route
 */
function dispatch ($route) {
    $route = trim($route, '/');
    
    $segments = explode('/', $route);
    
    $prefix = array_shift($segments);
    $prefix = $prefix ? $prefix : 'index';
    
    $suffix = array_shift($segments);
    $suffix = $suffix ? $suffix : 'index';
    
    $function = "route_{$prefix}_{$suffix}";
    $function = str_replace('-', '_', $function);
    $function = preg_replace('/[^\w\d_]/', '', $function);
    $function = trim($function, '_');
    
    if (!function_exists($function)) {
        not_found();
    }
    
    call_user_func_array($function, $segments);
}

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

/**
 * Check if current request is a post 
 * 
 * @return bool
 */
function is_post () {
    $method = array_get($_SERVER, 'REQUEST_METHOD', 'get');
    
    return strtolower($method) === 'post';
}

/**
 * View the view
 * 
 * @param string $__view
 * @param array $__data
 */
function view ($__view, array $__data = array()) {
    extract($__data);
    
    require sprintf('%s/themes/%s/%s.php', BASEPATH, theme(), $__view);
}


/**
 * Set/get theme
 * 
 * @param string $new_template
 * @return string
 */
function theme ($new_template = '') {
    static $template = 'default';
    
    if ($new_template) {
        $template = $new_template;
    }
    
    return $template;
}

/**
 * View the layout
 * 
 * @param string $view
 * @param array $data
 */
function layout ($view, array $data = array()) {
    $data['view'] = $view;
    
    view('layout', $data);
}

/**
 * Display 404 page
 */
function not_found () {
    header('HTTP/1.1 404 Not Found');
    
    die('404 - Not Found');
}

/**
 * Redirect to URL
 * 
 * @param string $path
 */
function redirect ($path) {
    $path = trim($path, '/');
    
    header("Location: /$path") and exit;
}


/**
 * Index page
 */
function route_index_index () {
    layout('posts/index', array(
        'title' => 'All posts',
        'posts' => posts_all()
    ));
}

/**
 * View a post
 * 
 * @param string $post_id
 */
function route_post_view ($post_id = 0) {
    $post = post_by_id($post_id);
    
    if (empty($post)) {
        not_found();
    }
    
    layout('posts/post', array(
        'title' => 'Post ' . $post['title'],
        'post'  => $post,
    ));
}

/**
 * Admin index page
 */
function route_admin_index () {
    kick_out_user();
    
    theme('admin');
    
    layout('index', array(
        'title' => 'Howdy, admin!'
    ));
}

/**
 * Kick out unauthorized user
 */
function kick_out_user () {
    if (!is_admin()) {
        redirect('admin/login');
    }
}

/**
 * Show login form
 * 
 * @param string $error
 */
function route_admin_login ($error = '') {
    theme('admin');
    
    view('auth', array(
        'title' => 'Log in, user!',
        'error' => $error
    ));
}

/**
 * Process authorization
 */
function route_admin_login_post () {
    $username = array_get($_POST, 'username');
    $password = md5(array_get($_POST, 'password'));
    
    if (auth_user($username, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        
        redirect('admin');
    }
    
    route_admin_login('Wrong username or password!');
}

/**
 * Log out admin
 */
function route_admin_logout () {
    session_destroy();
    
    redirect('');
}

/**
 * View all posts
 */
function route_admin_posts_view () {
    theme('admin');
    
    layout('posts/view', array(
        'title' => 'View posts',
        'posts' => db_browse('posts')
    ));
}

/**
 * Display post creation form
 */
function route_admin_posts_add () {
    if (is_post() && admin_posts_add($_POST)) {
        redirect('admin/posts-view');
    }
    
    theme('admin');
    
    layout('posts/modify', array(
        'title'  => 'View posts',
        'action' => 'add',
        
        'form' => array(
            'title' => array(
                'type' => 'input'
            ),
            
            'content' => array(
                'type' => 'text'
            )
        )
    ));
}

/**
 * Add a post
 * 
 * @param array $input
 */
function admin_posts_add (array $input) {
    return db_insert('posts', $input);
}

/**
 * Display post editing form
 * 
 * @param string $id
 */
function route_admin_posts_edit ($id = 0) {
    if (is_post() && admin_posts_edit($id, $_POST)) {
        redirect('admin/posts-view');
    }
    
    theme('admin');
    
    $post = db_find('posts', 'title, content', $id);
    
    if (!$post) {
        not_found();
    }
    
    layout('posts/modify', array(
        'title'  => 'View posts',
        'action' => 'edit',
        
        'form' => array(
            'title' => array(
                'type'  => 'input',
                'value' => array_get($post, 'title')
            ),
            
            'content' => array(
                'type'  => 'text',
                'value' => array_get($post, 'content')
            )
        )
    ));
}

/**
 * Add a post
 * 
 * @param string $id
 * @param array $input
 */
function admin_posts_edit ($id, array $input) {
    return db_update('posts', $input, $id) > 0;
}

/**
 * Remove a post
 * 
 * @param string $id
 */
function route_admin_posts_remove ($id = 0) {
    theme('admin');
    
    
}

/**
 * Get all posts
 * 
 * @return array
 */
function posts_all () {
    return db_select('SELECT id, date, title, content FROM posts ORDER BY id DESC');
}

/**
 * Get a post by id
 * 
 * @param string|int $id
 * @return array
 */
function post_by_id ($id) {
    return db_select(
        'SELECT id, date, title, content FROM posts WHERE id = ?', 
        array($id), true
    );
}

define('BASEPATH', chop(__DIR__, '/'));

/**
 * Setting error reporting
 * and error display 
 */
error_reporting(-1);
ini_set('display_errors', 1);

date_default_timezone_set('America/Los_Angeles');

session_start();

auth_user(
    array_get($_SESSION, 'username'),
    array_get($_SESSION, 'password')
);

db_connect(BASEPATH . '/content/db.sqlite');

dispatch(array_get($_GET, 'route', ''));

/**
 * Formats input date to 'dd.mm.yyyy' format
 * 
 * @param string $date
 * @return string
 */
function format_date ($date) {
    return date('d.m.Y', strtotime($date));
}