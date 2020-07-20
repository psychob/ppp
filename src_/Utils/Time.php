<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Utils;

    class Time
    {
        public const NANOSECONDS = 'ns';
        public const MICROSECONDS = 'µs';
        public const MILLISECONDS = 'ms';
        public const SECONDS = 's';
        public const MINUTES = 'm';

        public static function prettyPrecise(int $time, string $base = self::SECONDS): string
        {
            $timeState = [
                self::NANOSECONDS => 0,
                self::MICROSECONDS => 0,
                self::MILLISECONDS => 0,
                self::SECONDS => 0,
                self::MINUTES => 0,
            ];

            switch ($base) {
                /** @noinspection PhpMissingBreakStatementInspection */
                case self::NANOSECONDS:
                    $timeState[self::NANOSECONDS] = $time % 1000;
                    $time -= $timeState[self::NANOSECONDS];
                    $time /= 1000;

                /** @noinspection PhpMissingBreakStatementInspection */
                case self::MICROSECONDS:
                    $timeState[self::MICROSECONDS] = $time % 1000;
                    $time -= $timeState[self::MICROSECONDS];
                    $time /= 1000;

                /** @noinspection PhpMissingBreakStatementInspection */
                case self::MILLISECONDS:
                    $timeState[self::MILLISECONDS] = $time % 1000;
                    $time -= $timeState[self::MILLISECONDS];
                    $time /= 1000;

                /** @noinspection PhpMissingBreakStatementInspection */
                case self::SECONDS:
                    $timeState[self::SECONDS] = $time % 1000;
                    $time -= $timeState[self::SECONDS];
                    $time /= 1000;

                default:
                    $timeState[self::MINUTES] = $time;
            }

            $ret = '';
            if ($timeState[self::MINUTES] > 0) {
                $ret .= $timeState[self::MINUTES] .'m ';
            }

            if ($timeState[self::SECONDS] > 0) {
                $ret .= $timeState[self::SECONDS] .'s ';
            }

            if ($timeState[self::MILLISECONDS] > 0) {
                $ret .= $timeState[self::MILLISECONDS] .'ms ';
            }

            if ($timeState[self::MICROSECONDS] > 0) {
                $ret .= $timeState[self::MICROSECONDS] .'µs ';
            }

            if ($timeState[self::NANOSECONDS] > 0) {
                $ret .= $timeState[self::NANOSECONDS] .'ns ';
            }

            return rtrim($ret);
        }
    }
