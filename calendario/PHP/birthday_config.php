<?php
/**
 * Birthday Configuration
 * 
 * This file contains configuration settings for birthday generation in the calendar.
 */

// Number of years into the future to generate birthdays
// Default: 20 years (from current year to current year + 20)
define('BIRTHDAY_YEARS_FUTURE', 20);

// Optional: Number of years in the past to generate birthdays
// Set to 0 to start from current year only
define('BIRTHDAY_YEARS_PAST', 0);

/**
 * Get the year range for birthday generation
 * 
 * @return array Array with 'start' and 'end' year
 */
function getBirthdayYearRange() {
    $currentYear = date('Y');
    return [
        'start' => $currentYear - BIRTHDAY_YEARS_PAST,
        'end' => $currentYear + BIRTHDAY_YEARS_FUTURE
    ];
}
?>
