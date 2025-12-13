<?php

namespace EpiscopalDate\Tests;

use DateTimeImmutable;
use EpiscopalDate\EpiscopalDate;
use EpiscopalDate\LiturgicalYear;
use EpiscopalDate\Season;
use PHPUnit\Framework\TestCase;

class EpiscopalDateTest extends TestCase
{
    public function testCalculateEasterForKnownYear(): void
    {
        // Easter 2024 was March 31
        $easter = EpiscopalDate::calculateEaster(2024);
        $this->assertEquals('2024-03-31', $easter->format('Y-m-d'));

        // Easter 2025 is April 20
        $easter = EpiscopalDate::calculateEaster(2025);
        $this->assertEquals('2025-04-20', $easter->format('Y-m-d'));
    }

    public function testGetEaster(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $easter = $date->getEaster();
        $this->assertEquals('2024-03-31', $easter->format('Y-m-d'));
    }

    public function testGetAshWednesday(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $ashWednesday = $date->getAshWednesday();

        // Ash Wednesday 2024 was February 14
        $this->assertEquals('2024-02-14', $ashWednesday->format('Y-m-d'));
    }

    public function testGetMaundyThursday(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $maundyThursday = $date->getMaundyThursday();

        // Maundy Thursday 2024 was March 28
        $this->assertEquals('2024-03-28', $maundyThursday->format('Y-m-d'));
    }

    public function testGetGoodFriday(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $goodFriday = $date->getGoodFriday();

        // Good Friday 2024 was March 29
        $this->assertEquals('2024-03-29', $goodFriday->format('Y-m-d'));
    }

    public function testGetPalmSunday(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $palmSunday = $date->getPalmSunday();

        // Palm Sunday 2024 was March 24
        $this->assertEquals('2024-03-24', $palmSunday->format('Y-m-d'));
    }

    public function testGetPentecost(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $pentecost = $date->getPentecost();

        // Pentecost 2024 was May 19
        $this->assertEquals('2024-05-19', $pentecost->format('Y-m-d'));
    }

    public function testCalculateAdvent(): void
    {
        // First Sunday of Advent 2024 is December 1
        $advent = EpiscopalDate::calculateAdvent(2024);
        $this->assertEquals('2024-12-01', $advent->format('Y-m-d'));
    }

    public function testGetAdvent(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $advent = $date->getAdvent();
        $this->assertEquals('2024-12-01', $advent->format('Y-m-d'));
    }

    public function testGetLiturgicalYearBeforeAdvent(): void
    {
        // Before Advent 2024, we're in liturgical year 2024 (Year B)
        $date = new EpiscopalDate(new DateTimeImmutable('2024-11-01'));
        $this->assertEquals(LiturgicalYear::B, $date->getLiturgicalYear());
    }

    public function testGetLiturgicalYearAfterAdvent(): void
    {
        // After Advent 2024, we're in liturgical year 2025 (Year C)
        $date = new EpiscopalDate(new DateTimeImmutable('2024-12-15'));
        $this->assertEquals(LiturgicalYear::C, $date->getLiturgicalYear());
    }

    public function testGetSeasonAdvent(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-12-15'));
        $this->assertEquals(Season::Advent, $date->getSeason());
    }

    public function testGetSeasonChristmas(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-12-25'));
        $this->assertEquals(Season::Christmas, $date->getSeason());
    }

    public function testGetSeasonEpiphany(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-15'));
        $this->assertEquals(Season::Epiphany, $date->getSeason());
    }

    public function testGetSeasonLent(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-03-01'));
        $this->assertEquals(Season::Lent, $date->getSeason());
    }

    public function testGetSeasonEaster(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-04-15'));
        $this->assertEquals(Season::Easter, $date->getSeason());
    }

    public function testGetSeasonPentecost(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-07-01'));
        $this->assertEquals(Season::Pentecost, $date->getSeason());
    }

    public function testGenerateLiturgicalCalendarReturnsArray(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-01-01'));
        $calendar = $date->generateLiturgicalCalendar(2024);

        $this->assertIsArray($calendar);
        $this->assertNotEmpty($calendar);
    }

    public function testGetLiturgicalWeekReturnsString(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-07-01'));
        $week = $date->getLiturgicalWeek();

        $this->assertIsString($week);
        $this->assertStringContainsString('Pentecost', $week);
    }

    public function testDefaultConstructorUsesCurrentDate(): void
    {
        $date = new EpiscopalDate();
        $this->assertInstanceOf(DateTimeImmutable::class, $date->getDate());
    }

    public function testGetYear(): void
    {
        $date = new EpiscopalDate(new DateTimeImmutable('2024-06-15'));
        $this->assertEquals(2024, $date->getYear());
    }
}
