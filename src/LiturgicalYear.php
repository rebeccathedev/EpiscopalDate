<?php

namespace RebeccaTheDev\EpiscopalDate;

/**
 * Represents the three-year lectionary cycle (Year A, B, C) used in the Episcopal Church
 *
 * @author      Rebecca Peck <me@rebeccapeck.org>
 * @license     MIT
 */
enum LiturgicalYear: string {
    case A = 'A';
    case B = 'B';
    case C = 'C';

    /**
     * Calculate the liturgical year for a given calendar year
     */
    public static function forYear(int $year): self {
        return match ($year % 3) {
            0 => self::C,
            1 => self::A,
            2 => self::B,
        };
    }
}
