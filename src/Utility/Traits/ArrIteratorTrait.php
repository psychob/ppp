<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility\Traits;

    use Iterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapIterator;
    use PsychoB\WebFramework\Utility\Assert;
    use PsychoB\WebFramework\Utility\Enum\AssertPrimitiveTypeEnum;

    trait ArrIteratorTrait
    {
        public static function map($arr, callable $fnc): array
        {
            Assert::argumentTypeInstanceOf($arr, [
                Iterator::class,
                AssertPrimitiveTypeEnum::ARRAY,
            ], '$arr');

            if (is_array($arr)) {
                $arr = new \ArrayIterator($arr);
            }

            return static::toArray(new MapIterator($arr, $fnc));
        }

        public static function filter($arr, callable $fnc): array
        {
            Assert::argumentTypeInstanceOf($arr, [
                Iterator::class,
                AssertPrimitiveTypeEnum::ARRAY,
            ], '$arr');

            if (is_array($arr)) {
                $arr = new \ArrayIterator($arr);
            }

            return static::toArray(new FilterIterator($arr, $fnc));
        }
    }
