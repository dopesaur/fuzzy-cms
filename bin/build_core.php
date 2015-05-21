<?php

$basepath = dirname(__DIR__) . '/';

$build_config = file_get_contents(__DIR__ . '/build_core.json');
$build_config = json_decode($build_config, true);

$tags = array('<?php', '<?', '?>');

$replace = array('', '', '');

$content = '';
$i = 0;

foreach ($build_config['files'] as $file) {
    $file_content = file_get_contents($basepath . $file);
    
    if ($i !== 0) {
        $file_content = str_replace($tags, $replace, $file_content);
    }
    
    $content .= $file_content;
    
    $i += 1;
}

file_put_contents($basepath . $build_config['destination'], $content);