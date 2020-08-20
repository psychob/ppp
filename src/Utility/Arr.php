<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    use PsychoB\WebFramework\Collection\CollectionStream;
    use PsychoB\WebFramework\Collection\CollectionStreamInterface;

    class Arr
    {
        public static function hasKey(array $arr, $key): bool
        {
            return array_key_exists($key, $arr);
        }

        public static function in(array $arr, $element): bool
        {
            return in_array($element, $arr, true);
        }

        public static function mergeValues(array ...$arrays): array
        {
            $ret = [];

            foreach ($arrays as $array) {
                foreach ($array as $value) {
                    $ret[] = $value;
                }
            }

            return $ret;
        }

        public static function popBack(array &$array): void
        {
            array_pop($array);
        }

        public static function empty(array $arr): bool
        {
            return empty($arr);
        }

        public static function popFront(array &$arr)
        {
            $first = array_key_first($arr);
            $first = $arr[$first];

            $arr = array_slice($arr, 1);
            return $first;
        }

        public static function count(array $arr): int
        {
            return count($arr);
        }

        public static function len(array $arr): int
        {
            return count($arr);
        }

        /**
         * @param array|iterable ...$iterators
         *
         * @return iterable
         */
        public static function tieIterValStrict(...$iterators): iterable
        {
            $iterLengths = Arr::len($iterators);

            // reset all arrays to initial positions
            for ($it = 0; $it < $iterLengths; ++$it) {
                reset($iterators[$it]);
            }

            while (true) {
                // if key($arr) === null, then array have no more elements left
                $keySet = false;
                $lastKey = null;

                // check if array is still valid
                for ($it = 0; $it < $iterLengths; ++$it) {
                    $currentKey = self::currentKey($iterators[$it]);

                    if (!$keySet) {
                        $lastKey = $currentKey;
                        $keySet = true;
                    } else {
                        if (($currentKey === null && $lastKey !== null) ||
                            ($currentKey !== null && $lastKey === null)) {
                            throw new IteratorHasNoMoreElementsException($iterators, $iterators[$it]);
                        }

                        $lastKey = $currentKey;
                    }
                }

                // end of array
                if ($lastKey === null) {
                    return;
                }

                // return elements
                $ret = [];
                for ($it = 0; $it < $iterLengths; ++$it) {
                    $ret[] = current($iterators[$it]);
                }
                yield $ret;

                // move to next element
                for ($it = 0; $it < $iterLengths; ++$it) {
                    next($iterators[$it]);
                }
            }
        }

        public static function tieIterVal(array ...$iterators): iterable
        {
            $iterLengths = Arr::len($iterators);

            // reset all arrays to initial positions
            for ($it = 0; $it < $iterLengths; ++$it) {
                reset($iterators[$it]);
            }

            while (true) {
                // if key($arr) === null, then array have no more elements left
                $keySet = false;
                $lastKey = null;

                // check if array is still valid
                for ($it = 0; $it < $iterLengths; ++$it) {
                    $currentKey = key($iterators[$it]);

                    if (!$keySet) {
                        $lastKey = $currentKey;
                        $keySet = true;
                    } else {
                        $lastKey = $lastKey ?? $currentKey;
                    }
                }

                // end of array
                if ($lastKey === null) {
                    return;
                }

                // return elements
                $ret = [];
                for ($it = 0; $it < $iterLengths; ++$it) {
                    $curVal = current($iterators[$it]);

                    if ($curVal === false && key($iterators[$it]) === null) {
                        $curVal = Arr::emptyElement();
                    }

                    $ret[] = $curVal;
                }
                yield $ret;

                // move to next element
                for ($it = 0; $it < $iterLengths; ++$it) {
                    next($iterators[$it]);
                }
            }
        }

        public static function emptyElement(): object
        {
            static $emptyValue = null;

            if ($emptyValue === null) {
                $emptyValue = new class { };
            }

            return $emptyValue;
        }

        public static function filter(array $arr, $callable): array
        {
            return array_filter($arr, fn ($val, $key) => $callable($val, $key), ARRAY_FILTER_USE_BOTH);
        }

        public static function first($arr)
        {
            if ($arr instanceof \Generator) {
                return $arr->current();
            } else {
                $firstKey = array_key_first($arr);

                return $arr[$firstKey];
            }
        }

        public static function sort(array $arr, $cmpCallable): array
        {
            uasort($arr, $cmpCallable);
            return $arr;
        }

        /**
         * @param array|iterable $arrLike
         */
        public static function currentKey($arrLike)
        {
            if ($arrLike instanceof \Generator) {
                return $arrLike->key();
            }

            return key($arrLike);
        }

        /**
         * @param array|\Generator|iterable $arr
         *
         * @return array
         */
        public static function toArray($arr): array
        {
            return iterator_to_array($arr);
        }

        public static function stream($arr): CollectionStreamInterface
        {
            return new CollectionStream($arr);
        }

        public static function keys(array $arr): array
        {
            return array_keys($arr);
        }

        public static function firstOf($arr, $callback)
        {
            foreach ($arr as $key => $value) {
                if ($callback($value, $key)) {
                    return $value;
                }
            }

            return null;
        }

        public static function fetchBack($arr)
        {
        }
    }
