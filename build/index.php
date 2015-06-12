<?php function array_get (array $array, $key, $default = false) {if (isset($array[$key])) {return $array[$key];}$keys = explode('.', $key);$key = array_shift($keys);while ($key !== null && isset($array[$key])) {$array = $array[$key];$key = array_shift($keys);}if ($array !== null && $key === null) {return $array;}return $default;}function array_set (array &$array, $key, $value) {$array[$key] = $value;}function is_admin ($authorized = null) {static $admin = false;if ($authorized !== null) {$admin = $authorized;}return $admin === true;}function auth_user ($username, $password) {return is_admin($username === config('users.username') &&$password === config('users.password'));}function config ($key, $default = false) {static $storage = null;$storage or $storage = lazy_storage(basepath('content/_config'));return $storage($key, $default);}function data ($key, $default = false) {static $storage = null;$storage or $storage = lazy_storage(basepath('content/_data'));return $storage($key, $default);}function browse_content ($path = '') {$path = rtrim(basepath("content/$path"), '/');$files = glob("$path/*");return array_filter($files, function ($file) {return strpos($file, '.') !== 0 || !is_dir($file);});}function is_content_file ($file) {$path = basepath('content');return strpos($file, $path) === 0;}function content_file_exists ($file) {$path = basepath("content/$file");return file_exists($path);}function content_path ($path) {$directory = file_exists("$path/index.md");$file= file_exists("$path.md");if (!$file && !$directory) {return false;}return $file ? "$path.md" : "$path/index.md";}function db_connect ($path = '') {static $db = null;if ($db) {return $db;}$dsn = "sqlite:$path";$db = new PDO($dsn);$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);return $db;}function db_query ($query, array $parameters = array()) {$db = db_connect();$statement = $db->prepare($query);$statement->execute($parameters);return $statement;}function db_select ($query, array $parameters = array(), $one = false) {$statement = db_query($query, $parameters);$result = $one ? $statement->fetch() : $statement->fetchAll();return $result ? $result : array();}function db_browse ($table, $fields = '*') {$query = "SELECT $fields FROM $table ORDER BY id DESC";return db_select($query);}function db_find ($table, $fields = '*', $id) {return db_select("SELECT $fields FROM $table WHERE id = ?", array($id), true);}function db_insert ($table, array $data) {if (empty($data)) {return 0;}$columns = array_keys($data);$columns = array_map(function ($key) {return "`$key`";}, $columns);$columns = implode(',', $columns);$values = str_repeat('?,', count($data));$values = chop($values, ',');$query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columns, $values);db_query($query, array_values($data));return db_connect()->lastInsertId();}function db_update ($table, array $data, $id) {if (empty($data)) {return 0;}$key_values = array_map(function ($key) {return "`$key` = ?";},array_keys($data));$key_values = implode(', ', $key_values);$query = sprintf('UPDATE %s SET %s WHERE id = ?', $table, $key_values);$data   = array_values($data);$data[] = $id;return db_query($query, $data)->rowCount();}function load_extensions () {$path = basepath('extensions/*.php');foreach (glob($path, GLOB_NOSORT) as $extension) {require $extension;}}function is_post () {$method = array_get($_SERVER, 'REQUEST_METHOD', 'get');return strtolower($method) === 'post';}function basepath ($path = '') {return sprintf('%s/%s', BASEPATH, $path);}function clamp ($int, $min, $max) {$int = max($int, $min);return min($int, $max);}function pagination ($total, $limit, $page) {$page = (int)$page;$pages = ceil($total / $limit);$items = range(1, $pages);$page  = clamp($page, 1, $pages);$offset = $limit * ($page - 1);return compact('page', 'pages', 'items', 'offset', 'limit');}function dispatch ($route) {$route = trim($route, '/');$segments = explode('/', $route);$prefix = array_shift($segments);$suffix = array_shift($segments);if (!$function = route_exists($prefix, $suffix)) {not_found();}call_user_func_array($function, $segments);}function route_exists ($prefix, $suffix) {$prefix = $prefix ? $prefix : 'index';$suffix = $suffix ? $suffix : 'index';$route = url_to_name("route_{$prefix}_{$suffix}");$route = trim($route, '_');return function_exists($route) ? $route : '';}function route_content ($path) {if (preg_match('/_[\w\d\_\-]/', $path)) {not_found();}$path = trim($path, '/');$path = clean_url($path);$path = basepath("content/$path");$file = content_path($path);if (!$file) {return false;}$input = process_file($file);$layout = array_get($input, 'layout');$layout = basepath("content/_layouts/$layout.php");layout(file_exists($layout) ? $layout : 'page', $input);return true;}function base_url ($root = null, $base = null) {$root = $root ? $root : $_SERVER['DOCUMENT_ROOT'];$root = trim($root ? $root : BASEPATH, '/');$base = trim($base ? $base : BASEPATH, '/');if ($root === $base) {return '';}$base_url = str_replace($root, '', $base);return trim($base_url, '/');}function url () {$base = base_url();$url = implode('/', func_get_args());$url = "$base/$url/";$url = trim($url, '/');return "/$url";}function clean_url ($url) {$url = preg_replace('/\/+/', '/', $url);return str_replace('..', '', $url);}function storage (array $default = array()) {return function ($key = null, $value = null) use (&$default) {if ($key !== null && $value === null) {return array_get($default, $key);}if ($key !== null && $value !== null) {array_set($default, $key, $value);return;}return $default;};}function lazy_storage ($path, array $array = array()) {return function ($key, $default = false) use (&$array, $path) {$dot = strpos($key, '.');$first = $dot !== false ? substr($key, 0, $dot) : $key;if (!isset($array[$first])) {$config = "$path/$first.php";if (file_exists($config)) {$array[$first] = require $config;}}return array_get($array, $key, $default);};}function processors ($key = null, $value = null) {static $storage = null;$storage or $storage = storage();return $storage($key, $value);}function process ($name, $config) {$processor = processors($name);if (!is_callable($processor)) {throw new InvalidArgumentException("Processor '$name' doesn't exists!");}return $processor($config);}function process_file ($file) {$input = array();$content = capture(function () use ($file, &$input) {require $file;if (isset($data) && is_array($data)) {$input = $data;}});if (empty($input)) {list($input, $content) = process_config($content);}$processor = array_get($input, 'processor');$processor = $processor ? $processor : config('general.processing.content');$input['content'] = $processor ? process($processor, $content) : $config;return $input;}function process_config ($content) {$first  = strpos($content, '---');$second = strpos($content, '---', 1);if ($first !== 0 || $second === -1) {return array(array(), $content);}$config = substr($content, $first + 3, $second - 3);$newline = strpos($config, "\n");$processor = trim(substr($config, 0, $newline));$processor = $processor ? $processor : config('general.processing.header');$config = substr($config, $newline);return array(process($processor, $config),substr($content, $second + 3));}function render ($__view, array $__data = array()) {extract($__data);require $__view;}function layout ($view, array $data = array(), $layout = 'layout') {$data['view'] = $view;view($layout, $data);}function not_found () {header('HTTP/1.1 404 Not Found');die('404 - Not Found');}function redirect ($path) {$path = trim($path, '/');header("Location: /$path") and exit;}function capture ($callback) {ob_start();$callback();return ob_get_clean();}function url_to_name ($url) {$url = str_replace('-', '_', $url);return preg_replace('/[^\w\d_]/', '', $url);}function view_path ($theme, $view) {if (function_exists($function = "theme_{$theme}_{$view}") ||function_exists($function = "theme_{$view}")) {return $function;}return '';}function theme ($new_theme = '') {static $theme = 'default';if ($new_theme) {$theme = $new_theme;require_once sprintf('%s/themes/%s.php', BASEPATH, $theme);}return $theme;}function view ($__view, array $__data = array()) {if (strpos($__view, '/') === 0) {return render($__view, $__data);}$theme = theme();$view = str_replace('/', '_', $__view);$view = preg_replace('/[^\w\d_]/', '', $view);$function = view_path($theme, $view);if ($function) {return $function($__data);}throw new Exception("View/layout '$view' in theme '$theme' doesn't exists!");}function route_posts_index () {route_posts_view();}function route_posts_view ($page = 1) {if (!$page) {not_found();}$posts = posts_all_paginated(config('blog.posts', 5), $page);layout('posts/index', array('title'=> 'All posts','posts'=> $posts['posts'],'pagination' => $posts['pagination']));}function route_post_view ($post_id = 0) {$post = post_by_id($post_id);if (empty($post)) {not_found();}layout('posts/post', array('title' => "Post {$post['title']}",'post'  => $post,));}function route_admin_index () {kick_out_user();theme('admin');layout('index', array('title' => 'Howdy, admin!'));}function kick_out_user () {if (!is_admin()) {redirect('admin/login');}}function route_admin_login ($error = '') {theme('admin');view('auth', array('title' => 'Log in, user!','error' => $error));}function route_admin_login_post () {$username = array_get($_POST, 'username');$password = md5(array_get($_POST, 'password'));if (auth_user($username, $password)) {$_SESSION['username'] = $username;$_SESSION['password'] = $password;redirect('admin');}route_admin_login('Wrong username or password!');}function route_admin_logout () {session_destroy();redirect('');}function route_admin_posts_view () {theme('admin');layout('posts/view', array('title' => 'View posts','posts' => db_browse('posts')));}function posts_form () {return array('title' => array('type' => 'input'),'content' => array('type' => 'text'),'description' => array('type' => 'input'));}function route_admin_posts_add () {if (is_post() && admin_posts_add($_POST)) {redirect('admin/posts-view');}theme('admin');layout('posts/modify', array('title'  => 'View posts','action' => 'add','form' => posts_form()));}function admin_posts_add (array $input) {return db_insert('posts', $input);}function route_admin_posts_edit ($id = 0) {if (is_post() && admin_posts_edit($id, $_POST)) {redirect('admin/posts-view');}theme('admin');$post = db_find('posts', 'title, content, description', $id);if (!$post) {not_found();}$form = posts_form();foreach ($post as $key => $value) {$form[$key]['value'] = $value;}layout('posts/modify', array('title'  => 'View posts','action' => 'edit','form' => $form));}function admin_posts_edit ($id, array $input) {return db_update('posts', $input, $id) > 0;}function route_admin_posts_remove ($id = 0) {if (!$id) {not_found();}db_query('DELETE FROM posts WHERE id = ?', array($id));redirect('admin/posts-view');}function posts_all () {return db_select('SELECT id, date, title, content FROM posts ORDER BY id DESC');}function posts_all_paginated ($limit, $page = 1) {$total = posts_count();$pagination = pagination($total, $limit, $page);$limit = $pagination['limit'];$offset = $pagination['offset'];$posts = db_select('SELECT id, date, title, description FROM posts ORDER BY id DESC LIMIT ? OFFSET ?',array($limit, $offset));return compact('posts', 'pagination');}function posts_count () {$count = db_select('SELECT COUNT(*) FROM posts', array(), true);return current($count);}function post_by_id ($id) {return db_select('SELECT id, date, title, content, description FROM posts WHERE id = ?',array($id), true);}define('FUZZY_START', microtime(true));define('BASEPATH'   , chop(__DIR__, '/'));error_reporting(-1);ini_set('display_errors', 1);date_default_timezone_set(config('general.timezone', 'Europe/London'));session_start();load_extensions();auth_user(array_get($_SESSION, 'username'),array_get($_SESSION, 'password'));theme(config('general.theme', 'default'));db_connect(basepath('content/db.sqlite'));$route = array_get($_GET, 'route', '');if (!route_content($route)) {dispatch($route);}