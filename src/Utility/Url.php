<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    class Url
    {
        public static function join(string ...$urls): string
        {
            $ret = '';

            foreach ($urls as $part) {
                $lastRetHasSlash = Str::last($ret) === '/';
                $firstPartHashSlash = Str::first($part) === '/';

                if ($lastRetHasSlash && $firstPartHashSlash) {
                    $ret .= Str::sub($part, 1);
                } else if ($lastRetHasSlash || $firstPartHashSlash) {
                    $ret .= $part;
                } else {
                    $ret .= '/' . $part;
                }
            }

            return $ret;
        }
    }
