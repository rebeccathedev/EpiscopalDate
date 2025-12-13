<?php

namespace EpiscopalDate\Tests;

use EpiscopalDate\LegacyEpiscopalDate;
use PHPUnit\Framework\TestCase;

class LegacyEpiscopalDateTest extends TestCase
{
    public function testEasterDateReturnsTimestamp(): void
    {
        $timestamp = LegacyEpiscopalDate::easterDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-03-31', $date);
    }

    public function testEasterDateWithEmptyStringUsesCurrentYear(): void
    {
        $timestamp = LegacyEpiscopalDate::easterDate("");
        $this->assertIsInt($timestamp);
        $this->assertGreaterThan(0, $timestamp);
    }

    public function testAshWednesdayDate(): void
    {
        $timestamp = LegacyEpiscopalDate::ashWednesdayDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-02-14', $date);
    }

    public function testMaundyThursdayDate(): void
    {
        $timestamp = LegacyEpiscopalDate::maundyThursdayDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-03-28', $date);
    }

    public function testGoodFridayDate(): void
    {
        $timestamp = LegacyEpiscopalDate::goodFridayDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-03-29', $date);
    }

    public function testPalmSundayDate(): void
    {
        $timestamp = LegacyEpiscopalDate::palmSundayDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-03-24', $date);
    }

    public function testPentecostDate(): void
    {
        $timestamp = LegacyEpiscopalDate::pentecostDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-05-19', $date);
    }

    public function testAdventDate(): void
    {
        $timestamp = LegacyEpiscopalDate::adventDate(2024);
        $date = date('Y-m-d', $timestamp);
        $this->assertEquals('2024-12-01', $date);
    }

    public function testLiturgicalYearReturnsString(): void
    {
        $timestamp = strtotime('2024-11-01');
        $year = LegacyEpiscopalDate::liturgicalYear($timestamp);
        $this->assertEquals('B', $year);
    }

    public function testLiturgicalSeasonReturnsString(): void
    {
        $timestamp = strtotime('2024-12-15');
        $season = LegacyEpiscopalDate::liturgicalSeason($timestamp);
        $this->assertEquals('Advent', $season);
    }

    public function testLiturgicalWeekReturnsString(): void
    {
        $timestamp = strtotime('2024-07-01');
        $week = LegacyEpiscopalDate::liturgicalWeek($timestamp);
        $this->assertIsString($week);
        $this->assertStringContainsString('Pentecost', $week);
    }

    public function testLiturgicalCalendarReturnsArray(): void
    {
        $calendar = LegacyEpiscopalDate::liturgicalCalendar(2024);
        $this->assertIsArray($calendar);
        $this->assertNotEmpty($calendar);
    }
}
