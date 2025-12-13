<?php

namespace RebeccaTheDev\EpiscopalDate;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Modern PHP 8.1+ library for handling dates in the Episcopal Church USA
 *
 * This class provides methods for calculating liturgical dates and seasons
 * in the Episcopal Church (the American branch of the worldwide Anglican Communion).
 * As a Western Christian tradition, many of these functions are suitable for use
 * in other denominations as well.
 *
 * @author      Rebecca Peck <me@rebeccapeck.org>
 * @license     MIT
 */
class EpiscopalDate {
    private readonly DateTimeImmutable $date;

    public function __construct(?DateTimeImmutable $date = null) {
        $this->date = $date ?? new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * Get the date this instance represents
     */
    public function getDate(): DateTimeImmutable {
        return $this->date;
    }

    /**
     * Get the year from the current date
     */
    public function getYear(): int {
        return (int) $this->date->format('Y');
    }

    /**
     * Calculate Easter for a given year using the Computus algorithm
     *
     * This implementation works for any year, not just within Unix timestamp range.
     * Based on the algorithm found in PHP documentation comments.
     */
    public static function calculateEaster(int $year): DateTimeImmutable {
        // Use PHP's built-in function if available and within valid range
        if ($year >= 1970 && $year <= 2037) {
            $timestamp = easter_date($year);
            return (new DateTimeImmutable())->setTimestamp($timestamp);
        }

        // Computus algorithm for calculating Easter
        $golden = $year % 19;
        $leap = intdiv($year, 100);
        $modulo = ($leap - intdiv($leap, 4) - intdiv((8 * $leap + 13), 25) + 19 * $golden + 15) % 30;
        $paschalDays = $modulo - intdiv($modulo, 28) * (1 - intdiv($modulo, 28) * intdiv(29, ($modulo + 1)) * intdiv((21 - $golden), 11));
        $paschalDayOfWeek = ($year + intdiv($year, 4) + $paschalDays + 2 - $leap + intdiv($leap, 4)) % 7;
        $paschalNumber = $paschalDays - $paschalDayOfWeek;
        $month = 3 + intdiv(($paschalNumber + 40), 44);
        $day = $paschalNumber + 28 - 31 * intdiv($month, 4);

        return new DateTimeImmutable("{$year}-{$month}-{$day}");
    }

    /**
     * Get Easter for the year of this instance's date
     */
    public function getEaster(): DateTimeImmutable {
        return self::calculateEaster($this->getYear());
    }

    /**
     * Get Ash Wednesday (46 days before Easter)
     */
    public function getAshWednesday(): DateTimeImmutable {
        return $this->getEaster()->modify('-46 days');
    }

    /**
     * Get Maundy Thursday (3 days before Easter)
     */
    public function getMaundyThursday(): DateTimeImmutable {
        return $this->getEaster()->modify('-3 days');
    }

    /**
     * Get Good Friday (2 days before Easter)
     */
    public function getGoodFriday(): DateTimeImmutable {
        return $this->getEaster()->modify('-2 days');
    }

    /**
     * Get Palm Sunday (1 week before Easter)
     */
    public function getPalmSunday(): DateTimeImmutable {
        return $this->getEaster()->modify('-1 week');
    }

    /**
     * Get Pentecost (7 weeks after Easter)
     */
    public function getPentecost(): DateTimeImmutable {
        return $this->getEaster()->modify('+7 weeks');
    }

    /**
     * Calculate Advent for a given year
     *
     * Advent is the 4th Sunday before Christmas
     */
    public static function calculateAdvent(int $year): DateTimeImmutable {
        $christmas = new DateTimeImmutable("{$year}-12-25");
        return $christmas->modify('-4 weeks sunday');
    }

    /**
     * Get Advent for the year of this instance's date
     */
    public function getAdvent(): DateTimeImmutable {
        return self::calculateAdvent($this->getYear());
    }

    /**
     * Get the liturgical year (A, B, or C) for this date
     *
     * The liturgical year begins on Advent
     */
    public function getLiturgicalYear(): LiturgicalYear {
        $year = $this->getYear();
        $advent = self::calculateAdvent($year);

        // If we're past Advent, we're in the next liturgical year
        if ($this->date >= $advent) {
            $year++;
        }

        return LiturgicalYear::forYear($year);
    }

    /**
     * Get the liturgical season for this date
     */
    public function getSeason(): Season {
        $year = $this->getYear();
        $timestamp = $this->date->getTimestamp();

        $easter = self::calculateEaster($year)->getTimestamp();
        $advent = self::calculateAdvent($year)->getTimestamp();
        $ashWednesday = $this->getAshWednesday()->getTimestamp();
        $pentecost = $this->getPentecost()->getTimestamp();

        return match (true) {
            $timestamp >= $ashWednesday && $timestamp <= $easter => Season::Lent,
            $timestamp > $easter && $timestamp <= $pentecost => Season::Easter,
            $timestamp > $pentecost && $timestamp <= $advent => Season::Pentecost,
            $timestamp > $advent && $timestamp <= (new DateTimeImmutable("{$year}-12-24"))->getTimestamp() => Season::Advent,
            $this->isChristmasSeason($year, $timestamp) => Season::Christmas,
            $timestamp >= (new DateTimeImmutable("{$year}-01-06"))->getTimestamp() && $timestamp < $ashWednesday => Season::Epiphany,
            default => Season::Epiphany,
        };
    }

    /**
     * Check if a timestamp falls within the Christmas season
     */
    private function isChristmasSeason(int $year, int $timestamp): bool {
        $christmasStart = (new DateTimeImmutable("{$year}-12-25"))->getTimestamp();
        $christmasEnd = (new DateTimeImmutable("{$year}-12-31 23:59:59"))->getTimestamp();
        $epiphanyEve = (new DateTimeImmutable("{$year}-01-05"))->getTimestamp();

        return ($timestamp >= $christmasStart && $timestamp <= $christmasEnd) ||
               ($timestamp >= (new DateTimeImmutable("{$year}-01-01"))->getTimestamp() && $timestamp <= $epiphanyEve);
    }

    /**
     * Get the liturgical week for this date
     *
     * Returns a string like "Advent 2" or "Easter 5"
     */
    public function getLiturgicalWeek(): string {
        $sunday = $this->getSundayOfWeek();
        $calendar = $this->generateLiturgicalCalendar($this->getYear());

        return $calendar[$sunday->format('Y-m-d')] ?? '';
    }

    /**
     * Get the Sunday of the week for this date
     */
    private function getSundayOfWeek(): DateTimeImmutable {
        if ($this->date->format('w') === '0') {
            return $this->date->setTime(0, 0, 0);
        }

        return $this->date->modify('sunday -1 week');
    }

    /**
     * Generate a full liturgical calendar for a given year
     *
     * @return array<string, string> Array with date strings as keys and liturgical week as values
     */
    public function generateLiturgicalCalendar(int $year): array {
        $firstSunday = (new DateTimeImmutable("{$year}-01-01"))->modify('sunday');
        $calendar = [];
        $weekCounts = [];
        $currentDate = $firstSunday;

        while ($currentDate < new DateTimeImmutable(($year + 1) . "-01-01")) {
            $instance = new self($currentDate);
            $season = $instance->getSeason();

            // Pentecost counting starts at -1 (becomes 0 after first increment)
            if ($season === Season::Pentecost && !isset($weekCounts[$season->value])) {
                $weekCounts[$season->value] = -1;
            }

            $weekCounts[$season->value] = ($weekCounts[$season->value] ?? -1) + 1;
            $calendar[$currentDate->format('Y-m-d')] = $season->value . ' ' . $weekCounts[$season->value];

            $currentDate = $currentDate->modify('+1 week');
        }

        return $calendar;
    }
}
