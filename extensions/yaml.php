<?php

require basepath('extensions/yaml/Spyc.php');

add_processor('yaml', function ($config) { 
    return spyc_load($config); 
});