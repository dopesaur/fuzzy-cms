<?php

/**
 * Core builder
 * 
 * @arg string $json_config Path to JSON config
 */

if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(dirname(__DIR__)));
}

/**
 * Remove PHP tags
 * 
 * @param string $text
 * @return string
 */
function clean_tags ($text) {
    $tags = '/(^<\?(php)?|\?>\s*$)/';
    
    return preg_replace($tags, '', $text);
}

/**
 * Compress php file
 * 
 * @param string $content
 * @return string
 */
function compress_file ($content) {
    $content = remove_comments($content);
    $spaces = '/(\n|\r\n|\s{4,}|\t)/';
    
    $content = preg_replace($spaces, '', $content);
    
    return str_replace('<?php', '<?php ', $content);
}

/**
 * Remove all comments from PHP code
 * 
 * @link  http://stackoverflow.com/questions/503871/ ¬
 *        best-way-to-automatically-remove-comments-from-php-code
 * @param string $content
 * @return string
 */
function remove_comments ($content) {
    $commentTokens = array(T_COMMENT, T_DOC_COMMENT);

    $tokens = token_get_all($content);
    $new_content = '';
    
    foreach ($tokens as $token) {    
        if (is_array($token)) {
            if (in_array($token[0], $commentTokens)) {
                continue;
            }

            $token = $token[1];
        }

        $new_content .= $token;
    }
    
    return $new_content;
}

/**
 * Entry point, pass a config to build
 * 
 * @todo decompose
 * @throws \Exception
 * @param string $json_config
 * @param string $path
 */
function main ($json_config = null, $path = '') {
    $json_config = $json_config ? $json_config : __DIR__ . '/build_core.json';
    $basepath = ($path ? $path : BASEPATH) . '/';
    
    $build_config = file_get_contents($json_config);
    $build_config = json_decode($build_config, true);
    
    if (!$build_config) {
        throw new Exception(
            'Build config is not valid JSON or does not exists!!!'
        );
    }
    
    $files = $build_config['files'];
    
    $content = file_get_contents($basepath . array_shift($files));
    
    foreach ($files as $file) {
        $file_content = file_get_contents($basepath . $file);
        
        $content .= clean_tags($file_content);
    }
    
    if ($build_config['compress'] === true) {
        $content = compress_file($content);
    }
    
    file_put_contents($basepath . $build_config['destination'], $content);
}

call_user_func_array(
    'main', 
    array_slice($_SERVER['argv'], 1)
);