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
            return Str::join('/', ...$urls);
        }
    }
