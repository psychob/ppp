<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    class Str
    {
        public static function len(string $str): int
        {
            return strlen($str);
        }

        public static function findNextOf(string $content, array $toFind, int $offset, ?int $limit = null): ?int
        {
            return self::findNextOfWithInfo($content, $toFind, $offset, $limit)[0];
        }

        public static function findNextOfWithInfo(
            string $content,
            array $toFind,
            int $offset = 0,
            ?int $limit = null
        ): array {
            $it = $limit ?? Str::len($content);
            $found = null;

            foreach ($toFind as $str) {
                $nextToken = strpos($content, $str, $offset);

                if ($nextToken !== false) {
                    if ($it > $nextToken) {
                        $it = $nextToken;
                        $found = $str;
                    }
                }
            }

            return [
                $found === null ? null : $it,
                $found,
            ];
        }

        public static function sub(string $content, int $start, ?int $length = null): string
        {
            if ($length === null) {
                return substr($content, $start);
            } else {
                return substr($content, $start, $length);
            }
        }

        public static function findNext(string $text, string $substring, int $offset = 0): ?int
        {
            if ($offset) {
                $val = strpos($text, $substring, $offset);
                return is_int($val) ? $val : null;
            }

            $val = strpos($text, $substring);
            return is_int($val) ? $val : null;
        }

        public static function findNextNotOf(
            string $text,
            array $characters,
            ?int $offset = null,
            ?int $limit = null
        ): ?int {
            $offset = $offset ?? 0;
            $limit = $limit ?? Str::len($text);

            for ($it = $offset; $it < $limit; ++$it) {
                if (!Arr::in($characters, $text[$it])) {
                    return $it;
                }
            }

            return null;
        }

        public static function trim(string $str): string
        {
            return trim($str);
        }

        public static function rtrim(string $text, ?string $delimiters = null): string
        {
            if ($delimiters) {
                return rtrim($text, $delimiters);
            }

            return rtrim($text);
        }

        public static function ltrim(string $text): string
        {
            return ltrim($text);
        }

        public static function matchNextCharacter(string $text, array $characters, int $offset = 0): ?string
        {
            foreach (Arr::sort($characters, fn(string $left, string $right): int => Str::len($right) <=> Str::len($left)) as $str) {
                if (Str::compareEqualRightComplex($str, $text, $offset, Str::len($str))) {
                    return $str;
                }
            }

            return null;
        }

        public static function startsWith(string $text, string $startWith): bool
        {
            return strpos($text, $startWith, 0) === 0;
        }

        public static function compareEqualRightComplex(string $left, string $right, int $offset = 0, ?int $length = null): bool
        {
            return $left === Str::sub($right, $offset, $length);
        }

        public static function split(string $text, int $length = 1): array
        {
            return str_split($text, $length);
        }

        public static function replace(string $text, string $replaceWhat, string $replaceTo): string
        {
            return str_replace($replaceWhat, $replaceTo, $text);
        }

        public static function lower(string $text): string
        {
            return strtolower($text);
        }

        public static function isUpper(string $str): bool
        {
            return $str === strtoupper($str);
        }

        public static function first(string $str): ?string
        {
            if (Str::len($str) >= 1) {
                return $str[0];
            }

            return null;
        }

        public static function toUpper(string $str): string
        {
            return strtoupper($str);
        }

        public static function toLower(string $str): string
        {
            return strtolower($str);
        }

        public static function regExpMatch(string $pattern, string $subject): ?array
        {
            $m = [];
            if (preg_match($pattern, $subject, $m) === false) {
                return null;
            }

            return $m;
        }

        public static function is($str): bool
        {
            return is_string($str);
        }

        public static function explode(string $subject, string $delimiter): array
        {
            return explode($delimiter, $subject);
        }

        public static function last(string $str): ?string
        {
            if (empty($str)) {
                return null;
            }

            return $str[strlen($str) - 1];
        }

        public static function join(string $joiner, string ...$elements): string
        {
            $ret = '';

            foreach ($elements as $part) {
                $lastRetHasSlash = Str::last($ret) === $joiner;
                $firstPartHashSlash = Str::first($part) === $joiner;

                if ($lastRetHasSlash && $firstPartHashSlash) {
                    $ret .= Str::sub($part, 1);
                } else if ($lastRetHasSlash || $firstPartHashSlash) {
                    $ret .= $part;
                } else {
                    $ret .= $joiner . $part;
                }
            }

            return $ret;
        }
    }
