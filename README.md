EpiscopalDate
=============

EpiscopalDate is a small PHP class with a bunch of static methods for calculating dates and calendars in the Episcopal Church USA (the American branch of the worldwide Anglican Communion). As a Western Christian tradition, many of these functions are suitable for use in other denominations as well.

I wrote this as part of a rewrite of my Church's website and figured it might be useful to others as well.

 * Calculates the dates of Easter, Advent, Palm Sunday, Maundy Thursday, Good Friday and Pentecost.
 * Calculates the Liturgical year, based on the Revised Common Lectionary and the Book of Common Prayer.
 * Produces a full Liturgical calendar.

## Requirements

PHP 5.3+

## Installing

This class is PSR-4 autoloading compliant and uses namespaces. If you are using composer, installation is easy. Just add this to the require line in your composer.json file:

```
"peckrob/episcopaldate": "dev-master"
```

Alternatively, you can download the class and use it directly. It has no dependencies.

## Using

Using the class is easy. All of the date functions return UNIX timestamps that can be formatted however you like using the builtin PHP date() function.

```php
use EpiscopalDate\EpiscopalDate;

$easter_date = EpiscopalDate::easterDate(2014);
echo date("Y-m-d", $easter_date);
// Outputs 2014-04-20.
```

## Full Method Reference

```java
/**
 * Calculates the date of Easter on the Gregorian Calendar. This is based on
 * a function found in the comments here:
 * 
 * http://www.php.net/manual/en/function.easter-date.php
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Easter. 
 */
public static function easterDate($year = "");

/**
 * Calculates the date for Ash Wednesday, which is 46 days before Easter.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Ash Wednesday. 
 */
public static function ashWednesdayDate($year = "");

/**
 * Calculates the date of Mandy Thursday, which occurrs three days before 
 * Easter.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Mandy Thursday. 
 */
public static function maundyThursdayDate($year = "");

/**
 * Calculates the date of Good Friday, which occurrs two days before Easter.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Good Friday. 
 */
public static function goodFridayDate($year = "");

/**
 * Calculates the date for Palm Sunday, the Sunday before Easter.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Palm Sunday. 
 */
public static function palmSundayDate($year = "");

/**
 * Calculates the date of Pentecost, 7 weeks after Easter.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing Pentecost. 
 */
public static function pentecostDate($year = "");

/**
 * Calculates the date of Advent, which is defined as the 4th Sunday before 
 * Christmas.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return  int             A timestamp representing the first Sunday of 
 *                          Advent. 
 */
public static function adventDate($year = "");

/**
 * Calculates the Episcopal Liturgial Church Year, based on a date. The 
 * liturgical year begins on Advent.
 * 
 * @param   int     $timestamp  A timestamp.
 * @return  string              One of A, B, C.
 */
public static function liturgicalYear($timestamp = "");

/**
 * Returns a string representing the liturgical season.
 * 
 * @param   int     $timestamp  A timestamp.
 * @return  string              The liturgical season.
 */
public static function liturgicalSeason($timestamp = "");

/**
 * Returns the liturgical week.
 * 
 * @param   int     $timestamp  A timestamp.
 * @return  string              The current liturgical week.
 */
public static function liturgicalWeek($timestamp = "");

/**
 * Generages a full liturgical calendar, with the keys as the sundays of 
 * each week and the values as the liturgical season and week.
 * 
 * @param   int     $year   The year. If omitted, the current year.
 * @return type 
 */
public static function liturgicalCalendar($year);
```

## License

MIT