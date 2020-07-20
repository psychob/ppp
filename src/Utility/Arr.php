<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Utility;

    class Arr
    {
        public static function hasKey(array $array, $key): bool
        {
            return array_key_exists($key, $array);
        }

        public static function fetchElementLast(array $array, int $offset = 0)
        {
            return $array[count($array) - 1 - $offset];
        }

        public static function push(array $array, $value): array
        {
            $oV = $array;

            array_push($oV, $value);

            return $oV;
        }

        public static function popAndVerify(array &$array, $value)
        {
            return array_pop($array);
        }

        public static function stackCommonValues(array $left, array $right): array
        {
            $ret = [];

            $leftCount = count($left);
            $rightCount = count($right);

            for ($it = 0; $it < $leftCount && $it < $rightCount; ++$it) {
                if ($left[$it] !== $right[$it]) {
                    break;
                }

                $ret[] = $left[$it];
            }

            return $ret;
        }

        public static function removeElementsByKey(array $array, array $keys, bool $recountKeys = false): array
        {
            $ret = [];

            foreach ($array as $key => $value) {
                if (!Arr::inArray($keys, $key)) {
                    if ($recountKeys) {
                        $ret[] = $value;
                    } else {
                        $ret[$key] = $value;
                    }
                }
            }

            return $ret;
        }

        public static function stackElements(array ...$arrParameters): array
        {
            $ret = [];

            foreach ($arrParameters as $parameter) {
                foreach ($parameter as $val) {
                    $ret[] = $val;
                }
            }

            return $ret;
        }

        public static function inArray(array $array, $value): bool
        {
            return in_array($value, $array);
        }

        public static function mergeRecursive(array ...$arrParameters): array
        {
            return array_merge_recursive(...$arrParameters);
        }

        public static function popFront(array &$array)
        {
            return array_shift($array);
        }

        public static function first(array $array)
        {
            $firstKey = array_key_first($array);

            if ($firstKey === null) {
                return null;
            }

            return $array[$firstKey];
        }

        public static function last(array $array)
        {
            $lastKey = array_key_last($array);

            if ($lastKey === null) {
                return null;
            }

            return $array[$lastKey];
        }

        public static function pop(array &$array)
        {
            return array_pop($array);
        }

        public static function recountKeys(array $container): array
        {
            $ret = [];

            foreach ($container as $value) {
                $ret[] = $value;
            }

            return $ret;
        }

        public static function removeElementsByValue(array $array, array $values): array
        {
            $ret = [];

            foreach ($array as $key => $value) {
                if (!Arr::inArray($values, $value)) {
                    $ret[$key] = $value;
                }
            }

            return $ret;
        }
    }
