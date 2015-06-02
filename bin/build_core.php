<?php

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
 * @link  http://stackoverflow.com/questions/503871/ ¬
 *        best-way-to-automatically-remove-comments-from-php-code
 * @param string $content
 */
function compress_file ($content) {
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
    
    $content = $new_content;
    $spaces = '/(\n|\r\n|\s{4,}|\t)/';
    
    $content = preg_replace($spaces, '', $content);
    $content = str_replace('<?php', '<?php ', $content);
    
    return $content;
}

/**
 * Entry point, pass a config to build
 * 
 * @param string $json_config
 */
function main ($json_config) {
    $basepath = dirname(__DIR__) . '/';
    
    $build_config = file_get_contents($json_config);
    $build_config = json_decode($build_config, true);
    
    $files = $build_config['files'];
    
    $content = file_get_contents($basepath . array_shift($files));
    
    foreach ($files as $file) {
        $file_content = file_get_contents($basepath . $file);
        $file_content = clean_tags($file_content);
        
        $content .= $file_content;
    }
    
    if ($build_config['compress'] === true) {
        $content = compress_file($content);
    }
    
    file_put_contents($basepath . $build_config['destination'], $content);
}

/** Build core */
main(__DIR__ . '/build_core.json');