<?php

if (!function_exists('formatDate')) {
    function formatDate($dateString, $format = 'F j, Y g:i A') {
        if (empty($dateString)) {
            return '';
        }
        return date($format, strtotime($dateString));
    }
}
