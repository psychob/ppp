<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Core\Enum;

    use PsychoB\WebFramework\Utility\Arr;

    class AbstractEnum
    {
        private static $GlobalEnumCache = [];

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
            if (!Arr::hasKey(AbstractEnum::$GlobalEnumCache, static::class)) {
                AbstractEnum::$GlobalEnumCache[static::class] = (new \ReflectionClass(static::class))->getConstants();
            }

            return Arr::in(AbstractEnum::$GlobalEnumCache[static::class], $element);
        }
    }
