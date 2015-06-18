<?php

return array(
    /* Website title */
    'title' => 'Default',
    
    /* Default theme */
    'theme' => 'default',
    
    /* PHP date format */
    'date_format' => 'm-d-Y',
    
    /* Default timezone */
    'timezone'   => 'America/Los_Angeles',
    
    /**
     * Processing stuff
     * 
     * - header - header processor (config processor after the '---' delimeter)
     * - content - content processor (the ouput processor)
     * - escape - escape HTML
     * - raw - tell processor to avoid processing content
     */
    'processing' => array(
        'header'  => 'yaml',
        'content' => 'markdown',
        'escape'  => false,
        'raw'     => false
    )
);