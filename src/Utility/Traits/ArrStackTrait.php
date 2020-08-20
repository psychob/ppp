<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility\Traits;

    use PsychoB\WebFramework\Utility\Exceptions\EmptyStackException;

    /**
     * Trait containing functions used to manipulate stack arrays
     *
     * @author Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */
    trait ArrStackTrait
    {
        /**
         * Pop one element at front of stack. If stack is empty it will return NULL
         *
         * @param array $array
         * @return mixed|null
         */
        public static function popFront(array &$array)
        {
            if (count($array) === 0) {
                return null;
            }

            $first = array_key_first($array);
            $first = $array[$first];

            $array = array_slice($array, 1);
            return $first;
        }

        /**
         * Pop one element at back of stack. If stack is empty it will return NULL
         *
         * @param array $array
         * @return mixed|null
         */
        public static function popBack(array &$array)
        {
            return array_pop($array);
        }

        /**
         * Fetch one element at front of stack. If stack is empty it will return NULL. This function doesn't modify the
         * stack itself.
         *
         * @param array $array
         * @return mixed|null
         */
        public static function fetchFront(array $array)
        {
            if (count($array) === 0) {
                return null;
            }

            $firstKey = array_key_first($array);
            return $array[$firstKey];
        }

        /**
         * Fetch one element at back of stack. If stack is empty it will return NULL. This function doesn't modify the
         * stack itself.
         *
         * @param array $array
         * @return mixed|null
         */
        public static function fetchBack($array)
        {
            if (count($array) === 0) {
                return null;
            }

            $lastKey = array_key_last($array);
            return $array[$lastKey];
        }

        /**
         * Push one element at front of stack. This method will reset keys inside stack.
         *
         * @param array $array
         * @param mixed $value
         */
        public static function pushFront(array &$array, $value): void
        {
            $newArray = [ $value ] ;

            foreach ($array as $val) {
                $newArray[] = $val;
            }

            $array = $newArray;
        }

        /**
         * Push one element at back of stack. This method will NOT reset keys inside stack.
         *
         * @param array $array
         * @param mixed $value
         */
        public static function pushBack(array &$array, $value): void
        {
            $array[] = $value;
        }

        /**
         * Pop one element at front of stack.
         *
         * @param array $array
         * @return mixed|null
         *
         * @throws EmptyStackException If stack is empty.
         */
        public static function ensurePopFront(array &$array)
        {
            if (count($array) === 0) {
                throw new EmptyStackException('When trying to pop front');
            }

            return static::popFront($array);
        }

        /**
         * Pop one element at back of stack.
         *
         * @param array $array
         * @return mixed|null
         *
         * @throws EmptyStackException If stack is empty.
         */
        public static function ensurePopBack(array &$array)
        {
            if (count($array) === 0) {
                throw new EmptyStackException('When trying to pop back');
            }

            return static::popBack($array);
        }

        /**
         * Fetch one element at front of stack.
         *
         * @param array $array
         * @return mixed|null
         *
         * @throws EmptyStackException If stack is empty.
         */
        public static function ensureFetchFront(array $array)
        {
            if (count($array) === 0) {
                throw new EmptyStackException('When trying to fetch front');
            }

            return static::fetchFront($array);
        }

        /**
         * Fetch one element at back of stack.
         *
         * @param array $array
         * @return mixed|null
         *
         * @throws EmptyStackException If stack is empty.
         */
        public static function ensureFetchBack(array $array)
        {
            if (count($array) === 0) {
                throw new EmptyStackException('When trying to fetch back');
            }

            return static::fetchBack($array);
        }
    }
