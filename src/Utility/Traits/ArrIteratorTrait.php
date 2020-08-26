<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility\Traits;

    use ArrayIterator;
    use Generator;
    use Iterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterIterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterValueIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapIterator;
    use PsychoB\WebFramework\Utility\Arr;

    trait ArrIteratorTrait
    {
        /**
         * Map $arr with $fnc
         *
         * @param Iterator|array $arr
         * @param callable       $fnc
         *
         * @return array
         */
        public static function map($arr, callable $fnc): array
        {
            if (is_array($arr)) {
                $arr = new ArrayIterator($arr);
            }

            return static::toArray(new MapIterator($arr, $fnc));
        }

        /**
         * Filter $arr with $fnc
         *
         * @param Iterator|array $arr
         * @param callable       $fnc
         *
         * @return array
         */
        public static function filter($arr, callable $fnc): array
        {
            if (is_array($arr)) {
                $arr = new ArrayIterator($arr);
            }

            return static::toArray(new FilterIterator($arr, $fnc));
        }

        /**
         * @param array|Generator|iterable $arr
         *
         * @return array
         */
        public static function toArray($arr): array
        {
            if (is_array($arr)) {
                return $arr;
            }

            return iterator_to_array($arr);
        }

        /**
         * Get first element from $arr
         *
         * @param Generator|Iterator|array $arr
         * @return mixed|null
         *
         * @note If $arr is Iterator, it will be rewinded
         */
        public static function first($arr)
        {
            if ($arr instanceof Generator) {
                return $arr->current();
            }

            if ($arr instanceof Iterator) {
                $arr->rewind();

                return $arr->current();
            }

            return Arr::fetchFront($arr);
        }

        /**
         * Get last element from $arr
         *
         * @param Generator|Iterator|array $arr
         * @return mixed|null
         *
         * @note If $arr is Iterator or Generator it will be exhausted after using this method. Also infinite
         *       Generators will hold forever.
         */
        public static function last($arr)
        {
            if (is_array($arr)) {
                $arr = new ArrayIterator($arr);
            }

            $arr->rewind();
            $ret = null;

            while ($arr->valid()) {
                $ret = $arr->current();
                $arr->next();
            }

            return $ret;
        }

        /**
         * Get first value that supports $fnc requirement
         *
         * @param Generator|Iterator|array $arr
         * @param callable                 $fnc
         *
         * @return mixed|null
         *
         * @note If $arr is Iterator, it will be rewinded
         */
        public static function firstOf($arr, callable $fnc)
        {
            if (is_array($arr)) {
                $arr = new ArrayIterator($arr);
            }

            $filter = new FilterValueIterator($arr, fn($value, $key) => $fnc($value, $key));
            $filter->rewind();

            return $filter->valid() ? $filter->current() : null;
        }

        /**
         * Get first value that supports $fnc requirement
         *
         * @param Generator|Iterator|array $arr
         * @param callable                 $fnc
         *
         * @return mixed|null
         *
         * @note If $arr is Iterator or Generator it will be exhausted after using this method. Also infinite
         *       Generators will hold forever.
         */
        public static function lastOf($arr, callable $fnc)
        {
            if (is_array($arr)) {
                $arr = new ArrayIterator($arr);
            }

            $filter = new FilterValueIterator($arr, fn($value, $key) => $fnc($value, $key));

            $filter->rewind();
            $ret = null;

            while ($filter->valid()) {
                $ret = $filter->current();
                $filter->next();
            }

            return $ret;
        }
    }
