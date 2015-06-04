<?php

return array(
    /**
     * Default theme
     */
    'theme' => 'default',
    
    /**
     * Processing stuff
     * 
     * - header - header processor (config processor after the '---' delimeter)
     * - content - content processor (the ouput processor)
     * - escape - escape HTML
     */
    'processing' => array(
        'header'  => 'yaml',
        'content' => 'markdown',
        'escape'  => false
    )
);