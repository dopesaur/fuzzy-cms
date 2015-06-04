<?php

require basepath('extensions/markdown/Parsedown.php');

add_processor('markdown', function ($config) { 
    return markdown($config); 
});