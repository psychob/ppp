<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    class Obj
    {
        public static function field($obj, string $methodOrField)
        {
            if (is_array($obj)) {
                if (Arr::hasKey($obj, $methodOrField)) {
                    return $obj[$methodOrField];
                }
            }
        }
    }
