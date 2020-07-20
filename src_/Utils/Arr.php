<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Utils;

    use ArrayAccess;
    use Countable;

    final class Arr
    {
        /**
         * Pop first element from array
         *
         * @param $array
         *
         * @return mixed
         */
        public static function popFront(&$array)
        {
            return array_shift($array);
        }

        /**
         * @param Countable|array $elements
         *
         * @return bool
         */
        public static function empty($elements): bool
        {
            return count($elements) === 0;
        }

        /**
         * Check if $array has key named $key
         *
         * @param ArrayAccess|array $array
         * @param mixed             $key
         *
         * @return bool
         */
        public static function hasKey($array, $key): bool
        {
            return array_key_exists($key, $array);
        }

        public static function map($array, $callback)
        {
            /** @noinspection PhpParamsInspection */
            return iterator_to_array(self::iterateMap($array, $callback));
        }

        private static function iterateMap($array, $callback): iterable
        {
            foreach ($array as $k => $v) {
                $newV = $callback($v, $k);

                yield $k => $newV;
            }
        }

        public static function in($array, $element): bool
        {
            return in_array($element, $array);
        }

        public static function unique($array)
        {
            return array_unique($array);
        }

        public static function stackValues(array ...$arrays)
        {
            return iterator_to_array(self::iterateStackValues(...$arrays));
        }

        private static function iterateStackValues(array ...$arrays)
        {
            foreach ($arrays as $arr) {
                foreach ($arr as $v) {
                    yield $v;
                }
            }
        }

        public static function sort($array, $sortMethod)
        {
            $clone = $array;

            usort($clone, $sortMethod);

            return $clone;
        }

        public static function filter($array, $filterCallback)
        {
            return array_filter($array, fn (...$x) => !$filterCallback(...$x), ARRAY_FILTER_USE_BOTH);
        }

        public static function isArray($headers): bool
        {
            return is_array($headers);
        }
    }
