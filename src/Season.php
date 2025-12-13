<?php

namespace EpiscopalDate;

/**
 * Represents the liturgical seasons of the Episcopal Church
 *
 * @author      Rebecca Peck <me@rebeccapeck.org>
 * @license     MIT
 */
enum Season: string {
    case Advent = 'Advent';
    case Christmas = 'Christmas';
    case Epiphany = 'Epiphany';
    case Lent = 'Lent';
    case Easter = 'Easter';
    case Pentecost = 'Pentecost';
}
