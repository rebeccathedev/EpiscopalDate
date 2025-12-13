<?php

namespace EpiscopalDate;

use DateTimeImmutable;

/**
 * Legacy static facade for backwards compatibility with old API
 *
 * This class maintains the old timestamp-based API for backwards compatibility.
 * New code should use the main EpiscopalDate class instead.
 *
 * @author      Rebecca Peck <me@rebeccapeck.org>
 * @license     MIT
 * @deprecated  Use EpiscopalDate class instead
 */
class LegacyEpiscopalDate {
    /**
     * @param int|string $year
     */
    public static function easterDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        return EpiscopalDate::calculateEaster($year)->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function ashWednesdayDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->getAshWednesday()->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function maundyThursdayDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->getMaundyThursday()->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function goodFridayDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->getGoodFriday()->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function palmSundayDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->getPalmSunday()->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function pentecostDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->getPentecost()->getTimestamp();
    }

    /**
     * @param int|string $year
     */
    public static function adventDate(int|string $year = ""): int {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        return EpiscopalDate::calculateAdvent($year)->getTimestamp();
    }

    /**
     * @param int|string $timestamp
     */
    public static function liturgicalYear(int|string $timestamp = ""): string {
        $timestamp = empty($timestamp) ? time() : (int) $timestamp;
        $date = (new DateTimeImmutable())->setTimestamp($timestamp);
        $instance = new EpiscopalDate($date);
        return $instance->getLiturgicalYear()->value;
    }

    /**
     * @param int|string $timestamp
     */
    public static function liturgicalSeason(int|string $timestamp = ""): string {
        $timestamp = empty($timestamp) ? time() : (int) $timestamp;
        $date = (new DateTimeImmutable())->setTimestamp($timestamp);
        $instance = new EpiscopalDate($date);
        return $instance->getSeason()->value;
    }

    /**
     * @param int|string $timestamp
     */
    public static function liturgicalWeek(int|string $timestamp = ""): string {
        $timestamp = empty($timestamp) ? time() : (int) $timestamp;
        $date = (new DateTimeImmutable())->setTimestamp($timestamp);
        $instance = new EpiscopalDate($date);
        return $instance->getLiturgicalWeek();
    }

    /**
     * @param int|string $year
     * @return array<string, string>
     */
    public static function liturgicalCalendar(int|string $year = ""): array {
        $year = empty($year) ? (int) date("Y") : (int) $year;
        $instance = new EpiscopalDate(new DateTimeImmutable("{$year}-01-01"));
        return $instance->generateLiturgicalCalendar($year);
    }
}
