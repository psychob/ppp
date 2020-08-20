<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Core\Enum;

    class AbstractEnum
    {
        public static function hasAll(array $elements): bool
        {
            foreach ($elements as $element) {
                if (!static::has($element)) {
                    return false;
                }
            }

            return true;
        }

        public static function has($element): bool
        {
        }
    }
