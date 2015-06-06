<?php

/**
 * Formats input date to config format
 * 
 * @param string $date
 * @return string
 */
function format_date ($date) {
    static $format = null;
    
    $format or $format = config('general.date_format', 'm.d.Y');
    
    return date($format, strtotime($date));
}
