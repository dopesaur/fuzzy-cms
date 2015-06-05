<?php

/**
 * Formats input date to config format
 * 
 * @param string $date
 * @return string
 */
function format_date ($date) {
    return date(config('general.date_format', 'm.d.Y'), strtotime($date));
}
