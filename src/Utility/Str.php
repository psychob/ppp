<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Utility;

    use Stringy\Stringy;

    class Str
    {
        public static function startsWith(string $body, string $startsWith, bool $caseSensitive = true): bool
        {
            return Stringy::create($body)->startsWith($startsWith, $caseSensitive);
        }

        public static function substr(string $body, int $start, ?int $length = null): string
        {
            return Stringy::create($body)->substr($start, $length);
        }

        public static function toLower(string $body): string
        {
            return Stringy::create($body)->toLowerCase();
        }

        public static function replace(string $body, string $replaceWhat, string $replaceWith): string
        {
            return Stringy::create($body)->replace($replaceWhat, $replaceWith);
        }

        public static function upperCaseWords(string $body): string
        {
            return ucwords($body, ' ');
        }

        public static function split(string $body, string $delim): array
        {
            return explode($delim, $body);
        }
    }
