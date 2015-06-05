<?php

require basepath('extensions/yaml/Spyc.php');

processors('yaml', function ($config) { 
    return spyc_load($config); 
});