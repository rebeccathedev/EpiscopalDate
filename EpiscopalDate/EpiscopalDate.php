<?php

namespace EpiscopalDate;

/**
 * EpiscopalDate.php
 * 
 * This class contains a bunch of static methods mostly concerned with handling
 * dates in the Episcopal Church USA (the American branch of the worldwide 
 * Anglican Communion). As a Western Christian tradition, many of these 
 * functions are suitable for use in other denominations as well.
 * 
 * @author      Rob Peck <rob@robpeck.com>
 * @license     BSD
 * @package     Episcopal
 */
class EpiscopalDate {

    /**
     * Calculates the date of Easter on the Gregorian Calendar. This is based on
     * a function found in the comments here:
     * 
     * http://www.php.net/manual/en/function.easter-date.php
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Easter. 
     */
    public static function easterDate($year = "") {
        if (empty($year)) {
            $year = (int) date("Y");
        }

        // PHP has a builtin function for this, but it only works with valid
        // UNIX time. So if we're trying to calculate a date where UNIX time is
        // valid, we can use this instead of calculating it ourselves.
        if (function_exists("easter_date") && $year >= 1970 && $year <= 2037) {
            return easter_date($year);
        }

        $golden = $year % 19;
        $leap = (int) ($year / 100);
        $modulo = (int) ($leap - (int) ($leap / 4) - (int) ((8 * $leap + 13) / 25) + 19 * $golden + 15) % 30;
        $paschal_days = (int) $modulo - (int) ($modulo / 28) * (1 - (int) ($modulo / 28) * (int) (29 / ($modulo + 1)) * ((int) (21 - $golden) / 11));
        $paschal_day_of_week = ($year + (int) ($year / 4) + $paschal_days + 2 - $leap + (int) ($leap / 4)) % 7;
        $paschal_number = $paschal_days - $paschal_day_of_week;
        $month = 3 + (int) (($paschal_number + 40) / 44);
        $day = $paschal_number + 28 - 31 * ((int) ($month / 4));

        return mktime(0, 0, 0, $month, $day, $year);
    }

    /**
     * Calculates the date for Ash Wednesday, which is 46 days before Easter.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Ash Wednesday. 
     */
    public static function ashWednesdayDate($year = "") {
        $easter = EpiscopalDate::easterDate($year);
        return strtotime(date("Y-m-d", $easter) . " -46 days");
    }

    /**
     * Calculates the date of Mandy Thursday, which occurrs three days before 
     * Easter.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Mandy Thursday. 
     */
    public static function maundyThursdayDate($year = "") {
        $easter = EpiscopalDate::easterDate($year);
        return strtotime(date("Y-m-d", $easter) . " -3 days");
    }

    /**
     * Calculates the date of Good Friday, which occurrs two days before Easter.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Good Friday. 
     */
    public static function goodFridayDate($year = "") {
        $easter = EpiscopalDate::easterDate($year);
        return strtotime(date("Y-m-d", $easter) . " -2 days");
    }

    /**
     * Calculates the date for Palm Sunday, the Sunday before Easter.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Palm Sunday. 
     */
    public static function palmSundayDate($year = "") {
        $easter = EpiscopalDate::easterDate($year);
        return strtotime(date("Y-m-d", $easter) . " -1 week");
    }

    /**
     * Calculates the date of Pentecost, 7 weeks after Easter.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing Pentecost. 
     */
    public static function pentecostDate($year = "") {
        $easter = EpiscopalDate::easterDate($year);
        return strtotime(date("Y-m-d", $easter) . " +7 weeks");
    }

    /**
     * Calculates the date of Advent, which is defined as the 4th Sunday before 
     * Christmas.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return  int             A timestamp representing the first Sunday of 
     *                          Advent. 
     */
    public static function adventDate($year = "") {
        if (empty($year)) {
            $year = (int) date("Y");
        }

        return strtotime("$year-12-25 -5 weeks sunday");
    }

    /**
     * Calculates the Episcopal Liturgial Church Year, based on a date. The 
     * liturgical year begins on Advent.
     * 
     * @param   int     $timestamp  A timestamp.
     * @return  string              One of A, B, C.
     */
    public static function liturgicalYear($timestamp = "") {
        if(empty($timestamp)) {
            $timestamp = time();
        }
        
        $years = array("C", "A", "B");
        $year = date("Y", $timestamp);
        if ($timestamp > EpiscopalDate::adventDate($year)) {
            $year++;
        }

        return $years[($year % 3)];
    }

    /**
     * Returns a string representing the liturgical season.
     * 
     * @param   int     $timestamp  A timestamp.
     * @return  string              The liturgical season.
     */
    public static function liturgicalSeason($timestamp = "") {
        if(empty($timestamp)) {
            $timestamp = time();
        }
        
        $season = "";
        $year = date("Y", $timestamp);

        $easter = EpiscopalDate::easterDate($year);
        $advent = EpiscopalDate::adventDate($year);
        $ash = EpiscopalDate::ashWednesdayDate($year);
        $pentecost = EpiscopalDate::pentecostDate($year);

        if ($timestamp >= $ash && $timestamp <= $easter) {
            $season = "Lent";
        } elseif ($timestamp > $easter && $timestamp <= $pentecost) {
            $season = "Easter";
        } elseif ($timestamp > $pentecost && $timestamp <= $advent) {
            $season = "Pentecost";
        } elseif ($timestamp > $advent && $timestamp <= strtotime("$year-12-24")) {
            $season = "Advent";
        } elseif (($timestamp >= strtotime("$year-12-25") && $timestamp <= strtotime("$year-12-31 23:59:59")) ||
                ($timestamp >= strtotime("$year-01-01") && $timestamp <= strtotime($year . "-01-05"))) {
            $season = "Christmas";
        } elseif ($timestamp >= strtotime("$year-01-06") && $timestamp < $ash) {
            $season = "Epiphany";
        }

        return $season;
    }

    /**
     * Returns the liturgical week.
     * 
     * @param   int     $timestamp  A timestamp.
     * @return  string              The current liturgical week.
     */
    public static function liturgicalWeek($timestamp = "") {
        if(empty($timestamp)) {
            $timestamp = time();
        }
        
        if(date("w", $timestamp) === 0) {
            $sunday = strtotime(date("Y-m-d", $timestamp) . " 00:00:00");
        } else {
            $sunday = strtotime(date("Y-m-d", $timestamp) . " sunday - 1 week");
        }
        
        $year = date("Y", $timestamp);
        $calendar = EpiscopalDate::liturgicalCalendar($year);
        return $calendar[date("Y-m-d", $sunday)];
    }

    
    /**
     * Generages a full liturgical calendar, with the keys as the sundays of 
     * each week and the values as the liturgical season and week.
     * 
     * @param   int     $year   The year. If omitted, the current year.
     * @return type 
     */
    public static function liturgicalCalendar($year) {
        if (empty($year)) {
            $year = (int) date("Y");
        }
        
        $first_sunday = strtotime("$year-01-01 sunday");
        $ret = array();
        $z = array();
        $s = $first_sunday;
        while($s < strtotime($year + 1 . "-01-01")) {
            $m = EpiscopalDate::liturgicalSeason($s);
            
            if($m == "Pentecost" && !isset($z[$m])) {
                $z[$m] = -1;
            }
            $z[$m]++;
            $ret[date("Y-m-d", $s)] = EpiscopalDate::liturgicalSeason($s) . " " . $z[$m];
            $s += 604800;
        }
        
        return $ret;
    }

}

?>