<?php

/**
 * Formats input date to 'dd.mm.yyyy' format
 * 
 * @param string $date
 * @return string
 */
function format_date ($date) {
    return date('d.m.Y', strtotime($date));
}