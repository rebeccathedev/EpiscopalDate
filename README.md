# EpiscopalDate

A modern PHP 8.1+ library for handling dates in the Episcopal Church USA (the American branch of the worldwide Anglican Communion). As a Western Christian tradition, many of these functions are suitable for use in other denominations as well.

## Requirements

- PHP 8.1 or higher

## Installation

```bash
composer require rebeccathedev/episcopaldate
```

## Features

- Calculate liturgical dates (Easter, Advent, Ash Wednesday, etc.)
- Determine liturgical seasons and years
- Generate full liturgical calendars
- Modern PHP 8.1+ with enums, typed properties, and DateTimeImmutable
- Full test coverage with PHPUnit
- Backwards compatible legacy API

## Usage

### Modern API (Recommended)

```php
use EpiscopalDate\EpiscopalDate;
use DateTimeImmutable;

// Create an instance for today
$date = new EpiscopalDate();

// Or for a specific date
$date = new EpiscopalDate(new DateTimeImmutable('2024-03-15'));

// Get liturgical dates
$easter = $date->getEaster();              // DateTimeImmutable
$ashWednesday = $date->getAshWednesday();
$palmSunday = $date->getPalmSunday();
$pentecost = $date->getPentecost();
$advent = $date->getAdvent();

// Get season and year
$season = $date->getSeason();              // Season enum
echo $season->value;                       // "Lent"

$year = $date->getLiturgicalYear();        // LiturgicalYear enum
echo $year->value;                         // "A", "B", or "C"

// Get liturgical week
$week = $date->getLiturgicalWeek();        // "Lent 3"

// Generate a full liturgical calendar
$calendar = $date->generateLiturgicalCalendar(2024);
foreach ($calendar as $sunday => $weekName) {
    echo "$sunday: $weekName\n";
}

// Calculate dates for specific years
$easter2025 = EpiscopalDate::calculateEaster(2025);
$advent2025 = EpiscopalDate::calculateAdvent(2025);
```

### Enums

The library provides two enums for type safety:

```php
use EpiscopalDate\Season;
use EpiscopalDate\LiturgicalYear;

// Season enum
Season::Advent
Season::Christmas
Season::Epiphany
Season::Lent
Season::Easter
Season::Pentecost

// LiturgicalYear enum
LiturgicalYear::A
LiturgicalYear::B
LiturgicalYear::C

// Calculate liturgical year from calendar year
$year = LiturgicalYear::forYear(2024);  // LiturgicalYear::B
```

### Legacy API (Backwards Compatibility)

For backwards compatibility with the old timestamp-based API:

```php
use EpiscopalDate\LegacyEpiscopalDate;

// All methods return Unix timestamps
$easter = LegacyEpiscopalDate::easterDate(2024);
$ashWednesday = LegacyEpiscopalDate::ashWednesdayDate(2024);
$season = LegacyEpiscopalDate::liturgicalSeason(time());
$year = LegacyEpiscopalDate::liturgicalYear(time());
```

## Development

### Running Tests

```bash
composer install
vendor/bin/phpunit
```

### Project Structure

```
src/
├── EpiscopalDate.php          # Main class with modern API
├── LegacyEpiscopalDate.php    # Legacy backwards-compatible API
├── Season.php                  # Season enum
└── LiturgicalYear.php         # Liturgical year enum

tests/
├── EpiscopalDateTest.php
└── LegacyEpiscopalDateTest.php
```

## License

MIT License - see LICENSE file for details

## Author

Rebecca Peck <me@rebeccapeck.org>

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.